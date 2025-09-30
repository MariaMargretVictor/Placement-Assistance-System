<?php
session_start();
include '../connection.php'; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['c_email'];
    $password = $_POST['c_password'];

    $stmt = $conn->prepare("SELECT candidate_id, c_password FROM candidate WHERE c_email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($candidate_id, $hashed_password);
        $stmt->fetch();

        if (password_verify($password, $hashed_password)) { 
            $_SESSION['candidate_id'] = $candidate_id;
            $_SESSION['c_email'] = $email;
            header("Location: /placement/cadsh/candidate_dash.php"); 
            exit();
        } else {
            echo "<script>alert('Incorrect password. Try again!'); window.location.href='/placement/login/cand.php';</script>";
        }
    } else {
        echo "<script>alert('Candidate not found. Check your email!'); window.location.href='/placement/login/cand.php';</script>";
    }

    $stmt->close();
    $conn->close();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <link rel="stylesheet" href="log.css">
</head>
<body>
<div class="login-container">
    <div class="avatar">
        <img src="/placement/assets/account_circle_70dp_E8EAED_FILL0_wght400_GRAD0_opsz48.png" alt="">
    </div>
    <h2>Candidate</h2>
    <form action="cand.php" method="POST">
        <div class="input-container">
            <input type="email" name="c_email" placeholder="Candidate Email" required>
        </div>
        <div class="input-container">
            <input type="password" name="c_password" placeholder="Password" required>
        </div>
        <button type="submit" class="login-btn">LOGIN</button>
        <p class="forgot-password"><a href="#">Forgot Password?</a></p>
        <p class="create-account"><a href="/placement/candidate/candidate.php">Don't have an account? Create one</a></p>

    </form>
</div>
</body>
</html>