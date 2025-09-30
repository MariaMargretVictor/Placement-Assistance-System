<?php
include "../connection.php";
session_start();


$total_candidates_query = "SELECT COUNT(*) AS total FROM candidate";
$total_candidates_result = mysqli_query($conn, $total_candidates_query);
$total_candidates = mysqli_fetch_assoc($total_candidates_result)['total'];

$total_applications_query = "SELECT COUNT(*) AS total FROM apply";
$total_applications_result = mysqli_query($conn, $total_applications_query);
$total_applications = mysqli_fetch_assoc($total_applications_result)['total'];

$total_companies_query = "SELECT COUNT(*) AS total FROM company";
$total_companies_result = mysqli_query($conn, $total_companies_query);
$total_companies = mysqli_fetch_assoc($total_companies_result)['total'];

$total_shortlisted_query = "SELECT COUNT(*) AS total FROM shortlist";
$total_shortlisted_result = mysqli_query($conn, $total_shortlisted_query);
$total_shortlisted = mysqli_fetch_assoc($total_shortlisted_result)['total'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Overview</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }
        body {
            display: flex;
            background-color: #f4f4f4;
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
        <h2>Admin Dashboard</h2>
        <ul>
            <li class="nav_icon"><a href="/placement/dashboard/hme.php">Home</a></li>
            <li class="nav_icon"><a href="/placement/dashboard/adminsh.php">Candidates</a></li>
            <li class="nav_icon"><a href="/placement/dashboard/cndd.php">Applications</a></li>
            <li class="nav_icon"><a href="/placement/dashboard/cmpp.php">Companies</a></li>
            <li class="nav_icon"><a href="/placement/dashboard/srt.php">Shortlisted</a></li>
            <li class="nav_icon"><a href="/placement/home/web.php">Logout</a></li>
        </ul>
    </div>
    <div class="main-content">
        <header>
            <h2>Overview</h2>
        </header>
        <section class="stats">
            <div class="card">Total Candidates <span><?php echo $total_candidates; ?></span></div>
            <div class="card">Total Applications <span><?php echo $total_applications; ?></span></div>
            <div class="card">Total Companies <span><?php echo $total_companies; ?></span></div>
            <div class="card">Shortlisted Candidates <span><?php echo $total_shortlisted; ?></span></div>
        </section>
    </div>
</body>
</html>