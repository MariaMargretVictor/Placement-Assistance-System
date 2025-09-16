<?php
session_start(); 

include 'placement/connection.php'; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $admin_email = $_POST['admin_email'];
    $admin_password = $_POST['admin_password'];

    
    $stmt = $conn->prepare("SELECT admin_id, admin_password FROM admin WHERE admin_email = ?");
    $stmt->bind_param("s", $admin_email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        
        $stmt->bind_result($admin_id, $stored_password);
        $stmt->fetch();

        
        if ($admin_password === $stored_password) {
            $_SESSION['admin_id'] = $admin_id;
            $_SESSION['admin_email'] = $admin_email;
            header("Location: /placement/dashboard/hme.php"); 
            exit();
        } else {
            echo "<script>alert('Incorrect password. Try again!'); window.location.href='/placement/admin/admin2.php';</script>";
        }
    } else {
        echo "<script>alert('Admin not found. Check your email!'); window.location.href='/placement/admin/admin2.php';</script>";
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
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="login-container">
        <div class="avatar">
            <img src="../assets/account_circle_70dp_E8EAED_FILL0_wght400_GRAD0_opsz48.png" alt="">
        </div>
        <h2>Admin</h2>
        <form action="admin2.php" method="POST">
            <div class="input-container">
                <input type="email" name="admin_email" placeholder="Admin Email" required><br>
            </div>
            <div class="input-container">
                <input type="password" name="admin_password" placeholder="Password" required><br>
            </div>
            <button type="submit" class="login-btn">LOGIN</button>
            
        </form>
    </div>
</body>
</html>
