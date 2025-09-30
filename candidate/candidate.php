<?php
include '../connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $c_name = $_POST['c_name'];
    $c_email = $_POST['c_email'];
    $c_password = $_POST['c_password'];
    $c_cgpa = $_POST['c_cgpa'];
    $c_skills = implode(", ", $_POST['c_skills']);

    $hashed_password = password_hash($c_password, PASSWORD_DEFAULT);

    $target_dir = $_SERVER['DOCUMENT_ROOT'] . "/placement/uploads/";
    $target_file = $target_dir . basename($_FILES["c_resume"]["name"]);
    $file_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    if ($file_type != "pdf") {
        echo "<script>alert('Only PDF files are allowed!'); window.location.href='candidate.php';</script>";
        exit();
    }

    if (move_uploaded_file($_FILES["c_resume"]["tmp_name"], $target_file)) {
        
        $resume_path = "/placement/uploads/" . basename($_FILES["c_resume"]["name"]);

        $stmt = $conn->prepare("INSERT INTO candidate (c_name, c_email, c_password, c_cgpa, c_skills, c_resume) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssdss", $c_name, $c_email, $hashed_password, $c_cgpa, $c_skills, $resume_path);

        if ($stmt->execute()) {
            echo "<script>alert('Candidate Registered Successfully! Redirecting to login page...'); window.location.href='/placement/login/cand.php';</script>";
            exit();
        } else {
            echo "<script>alert('Error: Could not register candidate.'); window.location.href='candidate.php';</script>";
        }

        $stmt->close();
    } else {
        echo "<script>alert('File upload failed! Check folder permissions.'); window.location.href='candidate.php';</script>";
    }

    $conn->close();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Candidate Registration</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #3498db;
            --secondary-color: #2980b9;
            --accent-color: #e74c3c;
            --light-color: #ecf0f1;
            --dark-color: #2c3e50;
            --success-color: #2ecc71;
            --success-hover: #27ae60;
        }
        
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            background-size: cover;
            background-attachment: fixed;
            color: var(--dark-color);
            line-height: 1.6;
        }
        
        .container {
            background: rgba(255, 255, 255, 0.95);
            padding: 2.5rem;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 500px;
            margin: 2rem;
            transition: transform 0.3s ease;
        }
        
        .container:hover {
            transform: translateY(-5px);
        }
        
        h2 {
            text-align: center;
            color: var(--primary-color);
            margin-bottom: 1.5rem;
            font-size: 2rem;
            position: relative;
            padding-bottom: 0.5rem;
        }
        
        h2::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 80px;
            height: 3px;
            background: var(--primary-color);
            border-radius: 3px;
        }
        
        .form-group {
            margin-bottom: 1.5rem;
        }
        
        label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
            color: var(--dark-color);
        }
        
        input[type="text"],
        input[type="email"],
        input[type="password"],
        input[type="number"],
        input[type="file"] {
            width: 100%;
            padding: 0.8rem 1rem;
            border: 2px solid #ddd;
            border-radius: 8px;
            font-size: 1rem;
            transition: border 0.3s ease;
        }
        
        input:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.2);
        }
        
        .skills-container {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 0.8rem;
            margin-top: 0.5rem;
        }
        
        .skill-option {
            display: flex;
            align-items: center;
        }
        
        .skill-option input {
            margin-right: 0.5rem;
        }
        
        input[type="submit"] {
            background-color: var(--success-color);
            color: white;
            border: none;
            padding: 1rem;
            font-size: 1.1rem;
            font-weight: 600;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
            width: 100%;
            margin-top: 1rem;
        }
        
        input[type="submit"]:hover {
            background-color: var(--success-hover);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(46, 204, 113, 0.3);
        }
        
        .file-input-wrapper {
            position: relative;
            overflow: hidden;
            display: inline-block;
            width: 100%;
        }
        
        .file-input-wrapper input[type="file"] {
            position: absolute;
            font-size: 100px;
            opacity: 0;
            right: 0;
            top: 0;
            cursor: pointer;
        }
        
        .file-input-label {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0.8rem 1rem;
            background-color: #f8f9fa;
            border: 2px dashed #ddd;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .file-input-label:hover {
            border-color: var(--primary-color);
            background-color: #e9f7fe;
        }
        
        .file-input-text {
            color: #666;
        }
        
        .file-input-icon {
            color: var(--primary-color);
        }
        
        @media (max-width: 600px) {
            .container {
                padding: 1.5rem;
                margin: 1rem;
            }
            
            .skills-container {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
<div class="container">
    <h2>Candidate Registration</h2>
    <form action="candidate.php" method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label for="naam">Full Name</label>
            <input type="text" id="naam" name="c_name" placeholder="Enter your full name" required>
        </div>
        
        <div class="form-group">
            <label for="mail">Email Address</label>
            <input type="email" id="mail" name="c_email" placeholder="Enter your email" required>
        </div>
        
        <div class="form-group">
            <label for="pass">Password</label>
            <input type="password" id="pass" name="c_password" placeholder="Create a password" required>
        </div>
        
        <div class="form-group">
            <label for="cgpa">CGPA</label>
            <input type="number" id="cgpa" name="c_cgpa" step="0.01" min="0" max="10" placeholder="Enter your CGPA" required>
        </div>
        
        <div class="form-group">
            <label>Skills</label>
            <div class="skills-container">
                <div class="skill-option">
                    <input type="checkbox" id="python" name="c_skills[]" value="Python">
                    <label for="python">Python</label>
                </div>
                <div class="skill-option">
                    <input type="checkbox" id="java" name="c_skills[]" value="Java">
                    <label for="java">Java</label>
                </div>
                <div class="skill-option">
                    <input type="checkbox" id="cpp" name="c_skills[]" value="C++">
                    <label for="cpp">C++</label>
                </div>
                <div class="skill-option">
                    <input type="checkbox" id="javascript" name="c_skills[]" value="JavaScript">
                    <label for="javascript">JavaScript</label>
                </div>
                <div class="skill-option">
                    <input type="checkbox" id="sql" name="c_skills[]" value="SQL">
                    <label for="sql">SQL</label>
                </div>
                <div class="skill-option">
                    <input type="checkbox" id="flutter" name="c_skills[]" value="Flutter">
                    <label for="flutter">Flutter</label>
                </div>
                <div class="skill-option">
                    <input type="checkbox" id="fullstack" name="c_skills[]" value="Full Stack Developer">
                    <label for="fullstack">Full Stack</label>
                </div>
                <div class="skill-option">
                    <input type="checkbox" id="backend" name="c_skills[]" value="Backend Developer">
                    <label for="backend">Backend</label>
                </div>
                <div class="skill-option">
                    <input type="checkbox" id="bugbounty" name="c_skills[]" value="Bug Bounty">
                    <label for="bugbounty">Bug Bounty</label>
                </div>
                <div class="skill-option">
                    <input type="checkbox" id="gamedev" name="c_skills[]" value="Game Developer">
                    <label for="gamedev">Game Dev</label>
                </div>
            </div>
        </div>
        
        <div class="form-group">
            <label>Upload Resume (PDF only)</label>
            <div class="file-input-wrapper">
                <label class="file-input-label">
                    <span class="file-input-text" id="file-name">Choose a file...</span>
                    <i class="fas fa-cloud-upload-alt file-input-icon"></i>
                    <input type="file" id="resume" name="c_resume" accept="application/pdf" required onchange="updateFileName(this)">
                </label>
            </div>
        </div>
        
        <input type="submit" value="Register Now">
    </form>
</div>

<script>
    function updateFileName(input) {
        const fileNameElement = document.getElementById('file-name');
        if (input.files.length > 0) {
            fileNameElement.textContent = input.files[0].name;
            fileNameElement.style.color = '#2c3e50';
        } else {
            fileNameElement.textContent = 'Choose a file...';
            fileNameElement.style.color = '#666';
        }
    }
</script>
</body>
</html>