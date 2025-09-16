<?php
include "placement/connection.php";
session_start();

$candidateid = $_SESSION['candidate_id'];


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["withdraw_job_id"])) {
    $withdraw_job_id = $_POST['withdraw_job_id'];
    $delete_query = "DELETE FROM apply WHERE candidate_id = '$candidateid' AND job_id = '$withdraw_job_id'";
    
    if (mysqli_query($conn, $delete_query)) {
        echo "<script>alert('Application withdrawn successfully!'); window.location.href='jobpostings.php';</script>";
    } else {
        echo "<script>alert('Error withdrawing application: " . mysqli_error($conn) . "');</script>";
    }
}


$sql = "SELECT j.job_id, 
               j.job_title, 
               j.cgpa_criteria, 
               j.required_skills, 
               c.company_id, 
               c.company_name,
               a.apply_id AS user_applied,
               EXISTS (
                   SELECT 1 
                   FROM apply ap 
                   JOIN shortlist sh ON sh.apply_id = ap.apply_id 
                   WHERE ap.job_id = j.job_id
               ) AS job_closed
        FROM job j 
        JOIN company c ON j.company_id = c.company_id 
        LEFT JOIN apply a ON a.job_id = j.job_id AND a.candidate_id = '$candidateid'";

$result = mysqli_query($conn, $sql);


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["job_id"])) {
    $jobid = $_POST['job_id'];

    $query = "SELECT company_id FROM job WHERE job_id = '$jobid'";
    $company_result = mysqli_query($conn, $query);
    $company_row = mysqli_fetch_assoc($company_result);
    $companyid = $company_row['company_id'];

    $check_query = "SELECT * FROM apply WHERE candidate_id = '$candidateid' AND job_id = '$jobid'";
    $check_result = mysqli_query($conn, $check_query);

    if (mysqli_num_rows($check_result) == 0) {
        $insert_sql = "INSERT INTO `apply` (`candidate_id`, `company_id`, `job_id`) VALUES ('$candidateid', '$companyid', '$jobid')";
        if (mysqli_query($conn, $insert_sql)) {
            echo "<script>alert('Application submitted successfully!'); window.location.href='jobpostings.php';</script>";
        } else {
            echo "<script>alert('Error: " . mysqli_error($conn) . "');</script>";
        }
    } else {
        echo "<script>alert('You have already applied for this job!');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Job Postings</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            height: 100vh;
            width: 100vw;
            box-sizing: border-box;
        }
        .navbar {
            height: 10vh;
            background-color: rgb(26, 69, 198) !important;
        }
        .nav-link {
            font-weight: 600 !important;
            color: white !important;
            font-size: 20px;
            padding-right: 20px !important;
        }
        .navbar-brand {
            font-weight: 700;
            font-size: 28px;
            margin-left: 20px;
        }
        .table {
            margin-top: 20px;
        }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg bg-body-tertiary sticky-top">
    <div class="container-fluid">
        <a class="navbar-brand text-white" href="#">Placement Assistance System</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
            <div class="navbar-nav ms-md-auto mx-5 px-4">
                <a class="nav-link active" href="/placement/cadsh/candidate_dash.php">Home</a>
                <a class="nav-link" href="/placement/cadsh/jobpostings.php">Job Postings</a>
                <a class="nav-link" href="/placement/cadsh/checkapp.php">Check Application</a>
                <a class="nav-link" href="/placement/home/web.php">Log Out</a>
            </div>
        </div>
    </div>
</nav>

<main>
    <h3 class="text-center mt-3">Job Postings List</h3>
    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th scope="col">S No.</th>
                <th scope="col">Company Name</th>
                <th scope="col">Job Title</th>
                <th scope="col">CGPA Criteria</th>
                <th scope="col">Required Skills</th>
                <th scope="col">Apply</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $count = 1;
            while ($row = mysqli_fetch_assoc($result)) {
                $jobid = $row['job_id'];
                $has_applied = !empty($row['user_applied']);
                $job_closed = $row['job_closed']; 

                echo "<tr>";
                echo "<th scope='row'>" . $count . "</th>";
                echo "<td>" . htmlspecialchars($row['company_name']) . "</td>";
                echo "<td>" . htmlspecialchars($row['job_title']) . "</td>";
                echo "<td>" . htmlspecialchars($row['cgpa_criteria']) . "</td>";
                echo "<td>" . htmlspecialchars($row['required_skills']) . "</td>";
                echo "<td>";

                if ($job_closed) {
                    if ($has_applied) {
                        echo "<button class='btn btn-secondary' disabled>Applied</button>";
                    } else {
                        echo "<button class='btn btn-secondary' disabled>Closed</button>";
                    }
                } elseif ($has_applied) {
                    
                    echo "<form action='' method='POST'>
                            <input type='hidden' name='withdraw_job_id' value='" . $jobid . "'>
                            <button type='submit' class='btn btn-danger'>Withdraw</button>
                          </form>";
                } else {
                    
                    echo "<form action='' method='POST'>
                            <input type='hidden' name='job_id' value='" . $jobid . "'>
                            <button type='submit' class='btn btn-success'>Apply</button>
                          </form>";
                }

                echo "</td>";
                echo "</tr>";
                $count++;
            }
            ?>
        </tbody>
    </table>
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
