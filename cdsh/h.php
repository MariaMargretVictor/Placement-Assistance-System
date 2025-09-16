<?php
include "placement/connection.php";
session_start();

if (!isset($_SESSION['company_id'])) {
    header("Location: placement/home/web.php");
    exit();
}

$company_id = $_SESSION['company_id'];


$total_jobs_query = "SELECT COUNT(*) AS total FROM job WHERE company_id = ?";
$stmt1 = mysqli_prepare($conn, $total_jobs_query);
mysqli_stmt_bind_param($stmt1, "i", $company_id);
mysqli_stmt_execute($stmt1);
$total_jobs_result = mysqli_stmt_get_result($stmt1);
$total_jobs = mysqli_fetch_assoc($total_jobs_result)['total'];


$total_applied_query = "SELECT COUNT(DISTINCT a.candidate_id) AS total 
                        FROM apply a 
                        JOIN job j ON a.job_id = j.job_id 
                        WHERE j.company_id = ?";
$stmt2 = mysqli_prepare($conn, $total_applied_query);
mysqli_stmt_bind_param($stmt2, "i", $company_id);
mysqli_stmt_execute($stmt2);
$total_applied_result = mysqli_stmt_get_result($stmt2);
$total_applied = mysqli_fetch_assoc($total_applied_result)['total'];


$total_candidates_query = "SELECT COUNT(*) AS total FROM candidate";
$total_candidates_result = mysqli_query($conn, $total_candidates_query);
$total_candidates = mysqli_fetch_assoc($total_candidates_result)['total'];


$total_shortlisted_query = "SELECT COUNT(*) AS total 
                            FROM shortlist s
                            JOIN apply a ON s.apply_id = a.apply_id
                            JOIN job j ON a.job_id = j.job_id
                            WHERE s.status = 'shortlisted' AND j.company_id = ?";
$stmt3 = mysqli_prepare($conn, $total_shortlisted_query);
mysqli_stmt_bind_param($stmt3, "i", $company_id);
mysqli_stmt_execute($stmt3);
$total_shortlisted_result = mysqli_stmt_get_result($stmt3);
$total_shortlisted = mysqli_fetch_assoc($total_shortlisted_result)['total'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Company Dashboard - Overview</title>
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
        .main-content {
            margin-left: 260px;
            padding: 20px;
            width: calc(100% - 260px);
        }
        header {
            background: #191970;
            color: white;
            padding: 15px;
            text-align: center;
            font-size: 24px;
            margin-bottom: 20px;
        }
        .stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 20px;
        }
        .card {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            text-align: center;
            font-size: 18px;
        }
        .card span {
            display: block;
            font-size: 24px;
            font-weight: bold;
            color: #191970;
        }
        .nav_icon a{
            text-decoration:none;
            color:white;
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
            <li class="nav_icon"><a href="/placement/cdsh/shrtcmpy.php">Short Listed</a></li>
            <li class="nav_icon"><a href="/placement/cdsh/email.php">Email</a></li>
            <li class="nav_icon"><a href="/placement/home/web.php">Logout</a></li>

        </ul>
    </div>
    <div class="main-content">
        <header> 
            <h2>Overview</h2>
        </header>
        <section class="stats">
            <div class="card">Total Jobs Posted <span><?php echo $total_jobs; ?></span></div>
            <div class="card">Total Applied Candidates <span><?php echo $total_applied; ?></span></div>
            <div class="card">Total Candidates <span><?php echo $total_candidates; ?></span></div>
            <div class="card">Total Shortlisted Candidates <span><?php echo $total_shortlisted; ?></span></div>
        </section>
    </div>
</body>
</html>