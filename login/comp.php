<?php
session_start();
include '../connection.php'; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $company_name = $_POST['company_name'];
    $company_password = $_POST['company_password'];

    $stmt = $conn->prepare("SELECT company_id, company_password FROM company WHERE company_name = ?");
    $stmt->bind_param("s", $company_name);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($company_id, $stored_password);
        $stmt->fetch();

        if (password_verify($company_password, $stored_password)) {
            $_SESSION['company_id'] = $company_id;
            $_SESSION['company_name'] = $company_name;
            header("Location: /placement/cdsh/h.php"); 
            exit();
        } else {
            echo "<script>alert('Incorrect password. Try again!'); window.location.href='/placement/login/comp.php';</script>";
        }
    } else {
        echo "<script>alert('Company not found. Check your name!'); window.location.href='/placement/login/comp.php';</script>";
    }

    $stmt->close();
    $conn->close();
}
?>






<!DOCTYPE html>
<html lang="en">
<head>
    <style>
        /* Google Font */
@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500&display=swap');

* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: Arial, Helvetica, sans-serif;
}

body {
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
    background-color: #f1f1f1;
}

.login-container {
    background: #fff;
    padding: 30px;
    border-radius: 10px;
    box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
    text-align: center;
    width: 350px;
}

.login-container h2{
    font-weight: 700;
}
.avatar {
    width: 80px;
    height: 80px;
    background-color: #3b82f6;
    border-radius: 50%;
    display: flex;
    justify-content: center;
    align-items: center;
    margin: 0 auto 15px;
}

.avatar img{
    height: 90px;
}

h2 {
    font-weight: 500;
    margin-bottom: 15px;
}

.input-container {
    display: flex;
    align-items: center;
    background: #f1f1f1;
    padding: 10px;
    border-radius: 5px;
    margin-bottom: 15px;
}

.input-container input {
    border: none;
    outline: none;
    background: none;
    flex: 1;
    font-size: 16px;
}

.login-btn {
    background-color: #3b82f6;
    color: white;
    border: none;
    padding: 10px;
    border-radius: 5px;
    width: 100%;
    cursor: pointer;
    font-size: 16px;
}

.login-btn:hover {
    background-color: #2563eb;
}

.forgot-password {
    margin-top: 10px;
}

.forgot-password a {
    color: #3b82f6;
    text-decoration: none;
}

.forgot-password a:hover {
    text-decoration: underline;
}

.create-account {
    margin-top: 10px;
}

.create-account a {
    color: #3b82f6;
    text-decoration: none;
}

.create-account a:hover {
    text-decoration: underline;
}
    </style>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Company Login Page</title>
</head>
<body>
    <div class="login-container">
        <div class="avatar">
            <img src="/placement/assets/account_circle_70dp_E8EAED_FILL0_wght400_GRAD0_opsz48.png" alt="">
        </div>
        <h2>Company</h2>
        <form action="comp.php"  method="POST">
            <div class="input-container">
                <input type="text" name="company_name" placeholder="Company Name" required>
            </div>
            <div class="input-container">
                <input type="password" name="company_password"  placeholder="Password" required>
            </div>
            <button type="submit" class="login-btn">LOGIN</button>
            <p class="forgot-password"><a href="#">Forgot Password?</a></p>
            <p class="create-account"><a href="/placement/company/company.php">Don't have an account? Create one</a></p>
        </form>
    </div>
</body>
</html>

