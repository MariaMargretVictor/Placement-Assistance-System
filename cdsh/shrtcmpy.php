<?php
include "../connection.php";
session_start();

if (!isset($_SESSION['company_id'])) {
    header("Location: placement/home/web.php");
    exit();
}

$company_id = $_SESSION['company_id'];

$shortlisted_query = "SELECT s.apply_id, j.job_title, c.c_name AS candidate_name, c.c_email AS candidate_email
                      FROM shortlist s
                      JOIN apply a ON s.apply_id = a.apply_id
                      JOIN candidate c ON a.candidate_id = c.candidate_id
                      JOIN job j ON a.job_id = j.job_id
                      WHERE s.status = 'shortlisted' AND j.company_id = ?
                      ORDER BY j.job_title, c.c_name";

$stmt = mysqli_prepare($conn, $shortlisted_query);
mysqli_stmt_bind_param($stmt, "i", $company_id);
mysqli_stmt_execute($stmt);
$shortlisted_result = mysqli_stmt_get_result($stmt);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shortlisted Candidates - Placement Assistance System</title>
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
            padding: 30px;
            width: calc(100% - 270px);
        }

    /* Search Box */
    #searchInput {
        padding: 12px 15px;
        width: 100%;
        max-width: 400px;
        margin: 20px 0;
        font-size: 16px;
        border: 2px solid #e0e0e0;
        border-radius: 8px;
        transition: all 0.3s ease;
        outline: none;
    }

    #searchInput:focus {
        border-color: #4a6bff;
        box-shadow: 0 0 0 3px rgba(74, 107, 255, 0.1);
    }

    .table-container {
        margin-top: 20px;
        background-color: white;
        border-radius: 10px;
        box-shadow: 0 2px 15px rgba(0, 0, 0, 0.05);
        overflow: hidden;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        background-color: #fff;
    }

    th, td {
        padding: 15px;
        text-align: center;
        border-bottom: 1px solid #eaeaea;
    }

    th {
        background-color: #4a6bff;
        color: white;
        font-weight: 600;
        letter-spacing: 0.5px;
    }

    tr:not(:first-child):hover {
        background-color: #f8faff;
    }

    .shortlisted {
        background-color: #f0f9f0;
        color: #2e7d32;
    }

    .shortlisted:hover {
        background-color: #e0f3e0 !important;
    }

    /* No results message */
    td[colspan] {
        padding: 20px;
        color: #666;
        text-align: center;
        font-style: italic;
    }
        .nav_icon a {
            text-decoration: none;
            color: white;
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

    <div class="content">
        <header> 
            <h2>Short-Listed Candidates</h2>
        </header>
        

        <!-- Search Box -->
        <input type="text" id="searchInput" placeholder="Search by Candidate Name or Job Title">

        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Apply ID</th>
                        <th>Job Title</th>
                        <th>Candidate Name</th>
                        <th>Candidate Email</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (mysqli_num_rows($shortlisted_result) > 0) { 
                        while ($row = mysqli_fetch_assoc($shortlisted_result)) { ?>
                            <tr class="shortlisted">
                                <td><?php echo $row['apply_id']; ?></td>
                                <td><?php echo htmlspecialchars($row['job_title']); ?></td>
                                <td><?php echo htmlspecialchars($row['candidate_name']); ?></td>
                                <td><?php echo htmlspecialchars($row['candidate_email']); ?></td>
                            </tr>
                    <?php } } else { ?>
                        <tr><td colspan="4">No shortlisted candidates yet.</td></tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- JavaScript for search functionality -->
    <script>
        document.getElementById('searchInput').addEventListener('keyup', function () {
            let filter = this.value.toUpperCase();
            let rows = document.querySelectorAll("table tbody tr");

            rows.forEach(row => {
                let title = row.cells[1].textContent.toUpperCase();
                let name = row.cells[2].textContent.toUpperCase();

                if (title.includes(filter) || name.includes(filter)) {
                    row.style.display = "";
                } else {
                    row.style.display = "none";
                }
            });
        });
    </script>
</body>
</html>