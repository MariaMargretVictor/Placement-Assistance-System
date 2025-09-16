<?php
include "placement/connection.php";
session_start();

if (!isset($_SESSION['company_id'])) {
    header("Location: placement/home/web.php");
    exit();
}

$company_id = $_SESSION['company_id'];

$job_query = "SELECT job_id, job_title, job_description, cgpa_criteria, required_skills FROM job WHERE company_id = ? ORDER BY job_title";
$stmt = mysqli_prepare($conn, $job_query);
mysqli_stmt_bind_param($stmt, "i", $company_id);
mysqli_stmt_execute($stmt);
$job_result = mysqli_stmt_get_result($stmt);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Job Posted - Placement Assistance System</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }
        body {
            display: flex;
            background-color:rgb(255, 255, 255);
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
            padding: 20px;
            width: calc(100% - 270px);
        }
        .table-container {
            margin-top: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            background-color: #fff;
        }
        th, td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: center;
        }
        th {
            background-color: #007bff;
            color: white;
        }
        .nav_icon a {
            text-decoration: none;
            color: white;
        }

        /* Search Input Styling */
        #searchInput {
            padding: 8px;
            width: 250px;
            font-size: 14px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 6px;
            outline: none;
        }

        #searchInput:focus {
            border-color: #191970;
            box-shadow: 0 0 3px rgba(25, 25, 112, 0.3);
        }

        #noResult {
            text-align: center;
            color: #666;
            font-style: italic;
            margin-top: 10px;
            display: none;
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <h2>Company Dashboard</h2>
        <ul>
            <li class="nav_icon"><a href="/placement/cdsh/h.php">Home</a></li>
            <li class="nav_icon"><a href="/placement/Job/job.php">Job Postings</a></li>
            <li class="nav_icon"><a href="/placement/cdsh/jb.php">Job posted</a></li>
            <li class="nav_icon"><a href="/placement/cdsh/compsh.php">Candidate Applications</a></li>
            <li class="nav_icon"><a href="/placement/cdsh/shrtcmpy.php">Short-Listed</a></li>
            <li class="nav_icon"><a href="/placement/cdsh/email.php">Email</a></li>
            <li class="nav_icon"><a href="/placement/home/web.php">Logout</a></li>
        </ul>
    </div>

    <div class="content">
        <header> 
            <h2>Job Posted</h2>
        </header>

        <!-- 🔍 Search bar -->
        <input type="text" id="searchInput" placeholder="Search by Job ID or Job Title...">

        <div class="table-container">
            <table id="jobTable">
                <thead>
                    <tr>
                        <th>Job ID</th>
                        <th>Job Title</th>
                        <th>Job Description</th>
                        <th>CGPA Criteria</th>
                        <th>Required Skills</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (mysqli_num_rows($job_result) > 0) { 
                        while ($row = mysqli_fetch_assoc($job_result)) { ?>
                            <tr>
                                <td><?php echo $row['job_id']; ?></td>
                                <td><?php echo htmlspecialchars($row['job_title']); ?></td>
                                <td><?php echo htmlspecialchars($row['job_description']); ?></td>
                                <td><?php echo htmlspecialchars($row['cgpa_criteria']); ?></td>
                                <td><?php echo htmlspecialchars($row['required_skills']); ?></td>
                            </tr>
                    <?php } } else { ?>
                        <tr><td colspan="5">No job postings found.</td></tr>
                    <?php } ?>
                </tbody>
            </table>
            <div id="noResult">No job found.</div>
        </div>
    </div>

    <!-- 🔍 Search functionality -->
    <script>
        document.getElementById("searchInput").addEventListener("keyup", function () {
            let filter = this.value.toLowerCase();
            let rows = document.querySelectorAll("#jobTable tbody tr");
            let found = false;

            rows.forEach(function (row) {
                let jobId = row.cells[0]?.textContent.toLowerCase() || "";
                let jobTitle = row.cells[1]?.textContent.toLowerCase() || "";

                if (jobId.includes(filter) || jobTitle.includes(filter)) {
                    row.style.display = "";
                    found = true;
                } else {
                    row.style.display = "none";
                }
            });

            document.getElementById("noResult").style.display = found ? "none" : "block";
        });
    </script>
</body>
</html>