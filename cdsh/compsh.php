<?php
include "../connection.php";
session_start();

if (!isset($_SESSION['company_id'])) {
    header("Location: placement/home/web.php");
    exit();
}

$company_id = $_SESSION['company_id']; 

$job_query = "SELECT DISTINCT j.job_id, j.job_title, j.cgpa_criteria, j.required_skills, c.company_name 
              FROM job j
              JOIN company c ON j.company_id = c.company_id  
              JOIN apply a ON a.job_id = j.job_id
              WHERE j.company_id = ?";  

$stmt = mysqli_prepare($conn, $job_query);
mysqli_stmt_bind_param($stmt, "i", $company_id);
mysqli_stmt_execute($stmt);
$job_result = mysqli_stmt_get_result($stmt);

if (isset($_POST['filter_job_id'])) {
    $job_id = $_POST['filter_job_id'];
    
    $applications_query = "SELECT a.apply_id, c.c_cgpa, c.c_skills, j.cgpa_criteria, j.required_skills
        FROM apply a
        JOIN candidate c ON a.candidate_id = c.candidate_id
        JOIN job j ON a.job_id = j.job_id
        WHERE a.job_id = ? 
        AND j.company_id = ? 
        AND a.apply_id NOT IN (SELECT apply_id FROM shortlist)";
    $stmt = mysqli_prepare($conn, $applications_query);
    mysqli_stmt_bind_param($stmt, "ii", $job_id, $company_id);
    mysqli_stmt_execute($stmt);
    $applications_result = mysqli_stmt_get_result($stmt);
    
    $insert_values = array();
    
    while ($app = mysqli_fetch_assoc($applications_result)) {
        $apply_id = $app['apply_id'];
        $candidate_cgpa = $app['c_cgpa'];
        $cgpa_criteria = $app['cgpa_criteria'];
        $candidate_skills = array_map('trim', explode(",", $app['c_skills']));
        $required_skills = array_map('trim', explode(",", $app['required_skills']));
        
        $skills_match = true;
        foreach ($required_skills as $skill) {
            if (!in_array($skill, $candidate_skills)) {
                $skills_match = false;
                break;
            }
        }
        
        if ($candidate_cgpa >= $cgpa_criteria && $skills_match) {
            $insert_values[] = "('$apply_id', 'Shortlisted')";
        } else {
            $insert_values[] = "('$apply_id', 'Rejected')";
        }
    }
    
    if (!empty($insert_values)) {
        $insert_query = "INSERT INTO shortlist (apply_id, status) VALUES " . implode(", ", $insert_values);
        mysqli_query($conn, $insert_query);
    }    
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Company Dashboard - Placement Assistance System</title>
    <style>
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }
        body {
            display: flex;
            background-color: #f5f7fa;
        }
        .sidebar {
            width: 250px;
            background: #191970;
            color: white;
            height: 100vh;
            padding: 20px;
            position: fixed;
        }
        .sidebar h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        .sidebar ul {
            list-style: none;
            padding: 0;
        }
        .sidebar ul li {
            padding: 15px;
            cursor: pointer;
            transition: 0.3s;
        }
        .sidebar ul li:hover {
            background: #34495e;
        }
        header {
            background: #191970;
            color: white;
            padding: 15px;
            text-align: center;
            font-size: 24px;
            margin-bottom: 20px;
        }
        .content {
            margin-left: 270px;
            padding: 30px;
            width: calc(100% - 270px);
            background-color: #f5f7fa;
        }
        .filter-btn {
            background-color: #4a6bff;
            color: white;
            border: none;
            padding: 12px 20px;
            margin: 15px 0;
            cursor: pointer;
            font-size: 16px;
            border-radius: 6px;
            transition: all 0.3s ease;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        .filter-btn:hover {
            background-color: #3a56d4;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.15);
        }
        table {
            width: 100%;
            border-collapse: collapse;
            background-color: #fff;
            margin-top: 20px;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }
        th, td {
            padding: 12px 15px;
            border: 1px solid #e0e0e0;
            text-align: center;
        }
        th {
            background-color: #4a6bff;
            color: white;
            font-weight: 600;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        tr:hover {
            background-color: #f1f4ff;
        }
        .job-section {
            margin-bottom: 40px;
            background-color: white;
            padding: 25px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }
        .job-section h3 {
            color: #333;
            margin-bottom: 10px;
            font-size: 1.4rem;
        }
        .job-section p {
            color: #555;
            margin-bottom: 8px;
            font-size: 0.95rem;
        }
        .shortlisted {
            background-color: #e6f7e6 !important;
            color: #2e7d32;
            font-weight: 500;
        }
        .rejected {
            background-color: #ffebee !important;
            color: #c62828;
            font-weight: 500;
        }
        .nav_icon a {
            text-decoration: none;
            color: white;
        }
        .search-box {
            margin-bottom: 20px;
        }
        .search-box input {
            width: 100%;
            padding: 12px;
            font-size: 16px;
            border-radius: 8px;
            border: 1px solid #ccc;
        }
        .no-results {
            text-align: center;
            font-style: italic;
            color: #666;
        }
    </style>
    <script>
        function filterJobs() {
            const input = document.getElementById("searchInput").value.toLowerCase();
            const jobSections = document.querySelectorAll(".job-section");
            let found = false;

            jobSections.forEach(section => {
                const title = section.querySelector("h3").textContent.toLowerCase();
                if (title.includes(input)) {
                    section.style.display = "block";
                    found = true;
                } else {
                    section.style.display = "none";
                }
            });

            const noResultMsg = document.getElementById("noResults");
            noResultMsg.style.display = found ? "none" : "block";
        }
    </script>
</head>
<body>
    <div class="sidebar">
        <h2>Company Dashboard</h2>
        <ul>
            <li class="nav_icon"><a href="/placement/cdsh/h.php">Home</a></li>
            <li class="nav_icon"><a href="/placement/Job/job.php">Job Postings</a></li>
            <li class="nav_icon"><a href="/placement/cdsh/jb.php">Job posted</a></li>
            <li class="nav_icon"><a href="/placement/cdsh/compsh.php">Candidate Applications</a></li>
            <li class="nav_icon"><a href="/placement/cdsh/shrtcmpy.php">Short Listed</a></li>
            <li class="nav_icon"><a href="/placement/cdsh/email.php">Email</a></li>
            <li class="nav_icon"><a href="/placement/home/web.php">Logout</a></li>
        </ul>
    </div>

    <div class="content">
        <header><h2>Candidate Application</h2></header>

        <div class="search-box">
            <input type="text" id="searchInput" onkeyup="filterJobs()" placeholder="Search by Job Title...">
        </div>

        <p id="noResults" class="no-results" style="display:none;">No job found matching your search.</p>

        <?php while ($job_row = mysqli_fetch_assoc($job_result)) {
            $job_id = $job_row['job_id'];
            $job_title = htmlspecialchars($job_row['job_title']);
            $company_name = htmlspecialchars($job_row['company_name']);
            $cgpa_criteria = htmlspecialchars($job_row['cgpa_criteria']);
            $required_skills = htmlspecialchars($job_row['required_skills']);

            $shortlist_check_query = "SELECT COUNT(*) as count FROM shortlist s 
                                     JOIN apply a ON s.apply_id = a.apply_id 
                                     WHERE a.job_id = '$job_id'";
            $shortlist_check_result = mysqli_query($conn, $shortlist_check_query);
            $shortlist_count = mysqli_fetch_assoc($shortlist_check_result)['count'];
            $is_shortlisted = ($shortlist_count > 0);

            $application_query = "SELECT a.apply_id, c.c_name AS candidate_name, c.c_email AS candidate_email, 
                c.c_cgpa, c.c_skills, 
                s.status, s.apply_id as shortlist_id
                FROM apply a
                JOIN candidate c ON a.candidate_id = c.candidate_id
                JOIN job j ON a.job_id = j.job_id
                LEFT JOIN shortlist s ON s.apply_id = a.apply_id
                WHERE a.job_id = '$job_id' AND j.company_id = '$company_id'";
            $application_result = mysqli_query($conn, $application_query);
        ?>
            <div class="job-section">
                <h3><?php echo "$job_title at $company_name"; ?></h3>
                <p><strong>CGPA Criteria:</strong> <?php echo $cgpa_criteria; ?></p>
                <p><strong>Required Skills:</strong> <?php echo $required_skills; ?></p>

                <?php if (!$is_shortlisted) { ?>
                    <form method="POST" action="">
                        <input type="hidden" name="filter_job_id" value="<?php echo $job_id; ?>">
                        <button type="submit" class="filter-btn">Shortlist Candidates</button>
                    </form>
                <?php } else { ?>
                    <p><strong>Candidates have been shortlisted for this job.</strong></p>
                <?php } ?>

                <?php if (mysqli_num_rows($application_result) > 0) { ?>
                    <table>
                        <thead>
                            <tr>
                                <th>Apply ID</th>
                                <th>Candidate Name</th>
                                <th>Candidate Email</th>
                                <th>Candidate CGPA</th>
                                <th>Candidate Skills</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($app_row = mysqli_fetch_assoc($application_result)) { 
                                $status = $app_row['status'];
                                $row_class = '';
                                if ($status == 'Shortlisted') $row_class = 'shortlisted';
                                else if ($status == 'Rejected') $row_class = 'rejected';
                            ?>
                                <tr class="<?php echo $row_class; ?>">
                                    <td><?php echo $app_row['apply_id']; ?></td>
                                    <td><?php echo htmlspecialchars($app_row['candidate_name']); ?></td>
                                    <td><?php echo htmlspecialchars($app_row['candidate_email']); ?></td>
                                    <td><?php echo $app_row['c_cgpa']; ?></td>
                                    <td><?php echo htmlspecialchars($app_row['c_skills']); ?></td>
                                    <td><?php echo $status ? $status : 'Pending'; ?></td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                <?php } else { ?>
                    <p>No applications for this job.</p>
                <?php } ?>
            </div>
        <?php } ?>
    </div>
</body>
</html>