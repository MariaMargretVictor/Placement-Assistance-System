<?php
include "placement/connection.php";
session_start();

$company_query = "SELECT * FROM company";
$company_result = mysqli_query($conn, $company_query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Company Details</title>
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
        .details {
            background: white;
            padding: 20px;
            margin-bottom: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        h3 {
            margin-bottom: 15px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        th {
            background: #191970;
            color: white;
        }
        .nav_icon a{
            text-decoration:none;
            color:white;
        }
        #searchInput {
            padding: 8px;
            width: 250px;
            margin-bottom: 20px;
            font-size: 14px;
            border: 1px solid #ccc;
            border-radius: 6px;
            outline: none;
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
            <h2>Company Details</h2>
        </header>
        <section class="details">
            <h3>All Companies</h3>
            <input type="text" id="searchInput" placeholder="Search by Company ID or Name">
            <table>
                <thead>
                    <tr>
                        <th>Company ID</th>
                        <th>Company Name</th>
                        <th>Location</th>
                        <th>Contact</th>
                    </tr>
                </thead>
                <tbody id="companyTable">
                    <?php while ($row = mysqli_fetch_assoc($company_result)) { ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['company_id']); ?></td>
                            <td><?php echo htmlspecialchars($row['company_name']); ?></td>
                            <td><?php echo htmlspecialchars($row['company_address']); ?></td>
                            <td><?php echo htmlspecialchars($row['company_contactno']); ?></td>
                        </tr>
                    <?php } ?>
                    <?php if (mysqli_num_rows($company_result) == 0) { ?>
                        <tr><td colspan="4" style="text-align: center;">No companies found.</td></tr>
                    <?php } ?>
                </tbody>
            </table>
        </section>
    </div>

    <script>
        const searchInput = document.getElementById('searchInput');
        const tableBody = document.getElementById('companyTable');
        const originalRows = [...tableBody.querySelectorAll('tr')];

        searchInput.addEventListener('keyup', function () {
            const filter = this.value.toUpperCase();
            let visibleCount = 0;

            if (filter === '') {
                tableBody.innerHTML = '';
                originalRows.forEach(row => {
                    row.style.display = '';
                    tableBody.appendChild(row);
                });
            } else {
                tableBody.innerHTML = '';
                originalRows.forEach(row => {
                    const companyId = row.cells[0].textContent.toUpperCase();
                    const companyName = row.cells[1].textContent.toUpperCase();

                    if (companyId.includes(filter) || companyName.includes(filter)) {
                        tableBody.appendChild(row);
                        visibleCount++;
                    }
                });

                if (visibleCount === 0) {
                    tableBody.innerHTML = `<tr><td colspan="4" style="text-align: center;">No companies found.</td></tr>`;
                }
            }
        });
    </script>
</body>
</html>