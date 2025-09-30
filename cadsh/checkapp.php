<?php
include "../connection.php";
session_start();

$candidate_id = $_SESSION['candidate_id']; 


$sql = "SELECT j.job_title, c.company_name, c.company_address, 
               IFNULL(s.status, 'Pending') AS application_status
        FROM apply a
        JOIN job j ON a.job_id = j.job_id
        JOIN company c ON j.company_id = c.company_id
        LEFT JOIN shortlist s ON a.apply_id = s.apply_id
        WHERE a.candidate_id = '$candidate_id'";

$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Job Application Status</title>
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
        .shortlisted {
            color: green;
            font-weight: bold;
        }
        .rejected {
            color: red;
            font-weight: bold;
        }

    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg bg-body-tertiary sticky-top">
        <div class="container-fluid">
            <a class="navbar-brand text-white" href="#">Placement Assitance System</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
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
        <h3 class="text-center mt-3">Job Applications Status</h3>
        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th scope="col">S No.</th>
                    <th scope="col">Job Title</th>
                    <th scope="col">Company Name</th>
                    <th scope="col">Address</th>
                    <th scope="col">Status</th>
                </tr>
            </thead>
            <tbody class="table-group-divider">
                <?php
                $count = 1;
                while ($row = mysqli_fetch_assoc($result)) {
                    $status = $row['application_status'];
                    $status_class = ($status === 'Shortlisted') ? "shortlisted" : (($status === 'Rejected') ? "rejected" : "");
                
                    echo "<tr>";
                    echo "<th scope='row'>" . $count . "</th>";
                    echo "<td>" . htmlspecialchars($row['job_title']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['company_name']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['company_address']) . "</td>";
                    echo "<td class='$status_class'>" . htmlspecialchars($status) . "</td>";
                    echo "</tr>";
                    $count++;
                }

                if ($count == 1) {
                    echo "<tr><td colspan='5' class='text-center'>No applications found.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>