<?php
include "placement/connection.php";
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php'; 

function sendSMTPMail($toEmail, $toName, $subject, $body) {
    $mail = new PHPMailer(true);

    try {
        
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';  
        $mail->SMTPAuth = true;
        $mail->Username = 'krb69196@gmail.com';        
        $mail->Password = 'vtywkfuqmsriivok';           
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        
        $mail->setFrom('krb69196@gmail.com', 'Placement Cell');
        $mail->addAddress($toEmail, $toName);

        
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = $body;

        $mail->send();
        return true;
    } catch (Exception $e) {
        return false;
    }
}

session_start();

$company_id = $_SESSION['company_id'] ?? null;

if (!$company_id) {
    die("Unauthorized access.");
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['job_id'])) {
    $job_id = $_POST['job_id'];

    
    $query = "SELECT s.apply_id, c.c_email, c.c_name, j.job_title, comp.company_name
    FROM shortlist s
    JOIN apply a ON s.apply_id = a.apply_id
    JOIN candidate c ON a.candidate_id = c.candidate_id
    JOIN job j ON a.job_id = j.job_id
    JOIN company comp ON j.company_id = comp.company_id
    WHERE s.status = 'shortlisted'
    AND s.email_status='not_sent'
    AND j.job_id = $job_id
    LIMIT 100";

    $result = mysqli_query($conn, $query);

    while ($row = mysqli_fetch_assoc($result)) {
        $to = $row['c_email'];
        $subject = "Shortlisted for Job - " . $row['job_title'];
        
        $message = "Dear " . $row['c_name'] . ",<br><br>";
        $message .= "Congratulations! You have been <strong>shortlisted</strong> for the job: <strong>" . $row['job_title'] . "</strong> at <strong>" . $row['company_name'] . "</strong>.<br><br>";
        $message .= "Further updates regarding the interview process will be communicated soon.<br><br>";
        $message .= "Best regards,<br>";
        $message .= $row['company_name'] . " Team";
        
        $headers = "From: no-reply@placement.com";
    
        
        if (sendSMTPMail($to, $subject, $message, $headers)) {
            $apply_id = $row['apply_id'];
            mysqli_query($conn, "UPDATE shortlist SET email_status = 'sent' WHERE apply_id = $apply_id");
            header("Location: " . $_SERVER['PHP_SELF']);
            exit();
        }
    }
}


$searchTerm = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';

$jobs_query = "SELECT 
    job.job_id, 
    job.job_title,
    CASE 
        WHEN SUM(CASE WHEN s.email_status = 'not_sent' THEN 1 ELSE 0 END) > 0 THEN 'not_sent'
        ELSE 'sent'
    END AS email_overall_status
FROM job 
LEFT JOIN apply a ON a.job_id = job.job_id 
LEFT JOIN shortlist s ON s.apply_id = a.apply_id  
WHERE job.company_id = $company_id";

if (!empty($searchTerm)) {
    $jobs_query .= " AND job.job_title LIKE '%$searchTerm%'";
}

$jobs_query .= " GROUP BY job.job_id, job.job_title";
$jobs_result = mysqli_query($conn, $jobs_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Company Dashboard - Email Section</title>
    <style>
body {
    margin: 0;
    font-family: Arial, sans-serif;
    background-color: #f4f4f4;
    display: flex;
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
    font-size: 24px;
}

.sidebar ul {
    list-style: none;
    padding-left: 0;
}

.sidebar ul li {
    padding: 15px 10px;
    font-size: 16px;
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
.nav_icon a {
    text-decoration: none;
    color: white;
    display: block;
}

header {
    background: #191970;
    color: white;
    padding: 20px;
    text-align: center;
    font-size: 26px;
    font-weight: bold;
    margin-bottom: 30px;
}

.main-content {
        margin-left: 270px;
        padding: 30px;
        width: calc(100% - 270px);
        background-color: #f8fafc;
    }

    h3 {
        color: #2d3748;
        font-size: 1.5rem;
        margin: 30px 0 15px 0;
        padding-bottom: 10px;
        border-bottom: 2px solid #e2e8f0;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        background-color: white;
        margin: 20px 0 40px 0;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
    }

    th, td {
        padding: 15px;
        text-align: center;
        border: 1px solid #edf2f7;
    }

    th {
        background-color: #4a6bff;
        color: white;
        font-weight: 600;
        font-size: 0.95rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    tr:nth-child(even) {
        background-color: #f8fafc;
    }

    tr:hover {
        background-color: #f0f4ff;
    }

    .btn {
        background-color: #4a6bff;
        color: white;
        padding: 12px 24px;
        border: none;
        border-radius: 6px;
        font-size: 1rem;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 2px 5px rgba(74, 107, 255, 0.2);
        margin-bottom: 20px;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .btn:hover {
        background-color: #3a56d4;
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(74, 107, 255, 0.3);
    }

    .notice {
        color: #718096;
        font-style: italic;
        padding: 15px;
        background-color: #f8fafc;
        border-radius: 6px;
        margin: 20px 0;
        border-left: 4px solid #cbd5e0;
    }

    /* Status badges */
    td:last-child {
        font-weight: 500;
    }

    td:last-child:contains("Sent") {
        color: #38a169;
    }

    td:last-child:contains("Not Sent") {
        color: #e53e3e;
    }
    </style>
</head>
<body>
    <div class="sidebar">
        <h2>Company Dashboard</h2>
        <ul>
            <li class="nav_icon"><a href="/placement/cdsh/h.php">Home</a></li>
            <li class="nav_icon"><a href="/placement/Job/job.php">Job Postings</a></li>
            <li class="nav_icon"><a href="/placement/cdsh/jb.php">Job Posted</a></li>
            <li class="nav_icon"><a href="/placement/cdsh/compsh.php">Candidate Applications</a></li>
            <li class="nav_icon"><a href="/placement/cdsh/shrtcmpy.php">Shortlisted</a></li>
            <li class="nav_icon"><a href="/placement/cdsh/email.php">Email</a></li>
            <li class="nav_icon"><a href="/placement/home/web.php">Logout</a></li>
        </ul>
    </div>

    <div class="main-content">
        <header><h2>Email Section</h2></header>
        <form method="GET" style="margin-bottom: 30px;">
    <input type="text" name="search" placeholder="Search by Job Title" value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>" style="padding: 10px; font-size: 16px; width: 300px;">
    <button type="submit" class="btn">🔍 Search</button>
</form>

        <?php while ($job = mysqli_fetch_assoc($jobs_result)): ?>
            <?php
                $job_id = $job['job_id'];
                $shortlisted_query = "SELECT s.apply_id, c.c_name, c.c_email, c.candidate_id,s.email_status
                                      FROM shortlist s
                                      JOIN apply a ON s.apply_id = a.apply_id
                                      JOIN candidate c ON a.candidate_id = c.candidate_id
                                      WHERE a.job_id = $job_id AND s.status = 'shortlisted'";
                $shortlisted_result = mysqli_query($conn, $shortlisted_query);
            ?>

<h3><?php echo htmlspecialchars($job['job_title']); ?></h3>

            <?php if (mysqli_num_rows($shortlisted_result) > 0): ?>
                <form method="POST">
                    <input type="hidden" name="job_id" value="<?php echo $job_id; ?>">
                   <?php if($job['email_overall_status']=='not_sent')
                   {
                    echo "<button type='submit' class='btn'>📧 Send Email</button>";
                   }
                   ?>
                </form>

                <table>
                    <tr>
                        <th>Candidate ID</th>
                        <th>Apply ID</th>
                        <th>Candidate Name</th>
                        <th>Email</th>
                        <th>Status</th>
                        
                    </tr>
                    <?php while ($row = mysqli_fetch_assoc($shortlisted_result)): ?>
                        <tr>
                            <td><?php echo $row['candidate_id']; ?></td>
                            <td><?php echo $row['apply_id']; ?></td>
                            <td><?php echo htmlspecialchars($row['c_name']); ?></td>
                            <td><?php echo htmlspecialchars($row['c_email']); ?></td>
                            <td><?php echo htmlspecialchars($row['email_status']=='sent'?'Sent':'Not Sent'); ?></td>

                            
                        </tr>
                    <?php endwhile; ?>
                </table>
            <?php else: ?>
                <p class="notice">No shortlisted candidates pending email for this job.</p>
            <?php endif; ?>
        <?php endwhile; ?>
    </div>
</body>
</html>