<?php
session_start();
include 'placement/connection.php';

if (!isset($_SESSION['company_id'])) {
    echo "<script>alert('Please log in first!'); window.location.href='/placement/login/comp.php';</script>";
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $job_title = $_POST['job_title'];
    $cgpa_criteria = $_POST['cgpa_criteria'];
    $skills = isset($_POST['skills']) ? implode(",", $_POST['skills']) : ""; 
    $job_description = $_POST['job_description'];
    $company_id = $_SESSION['company_id']; 

    $sql = "INSERT INTO job (job_title, job_description, cgpa_criteria, required_skills, company_id) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssdsi", $job_title, $job_description, $cgpa_criteria, $skills, $company_id);
    
    if ($stmt->execute()) {
        echo "<script>alert('Job posted successfully!'); window.location.href='/placement/cdsh/compsh.php';</script>";
        exit();
    } else {
        echo "<p>Error: " . $stmt->error . "</p>";
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
        textarea{
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
    <h2>Job Posting</h2>
    <form action="job.php" method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label for="job_title">Job Title</label>
            <input type="text" name="job_title" placeholder="Enter Job Title" required>
        </div>
        
        <div class="form-group">
            <label for="mail">Minimum CGPA Required</label>
            <input type="number" name="cgpa_criteria" placeholder="Enter minimum CGPA" step="0.1" required>
        </div>
        
        <div class="form-group">
            <label for="pass">Job Description</label>
            <textarea name="job_description" class="textarea-field" rows="5" placeholder="Add Job Description..." required></textarea>
        </div>
    
        <div class="form-group">
            <label>Required Skills</label>
            <div class="skills-container">
                <div class="skill-option">
                    <input type="checkbox" id="python" name="skills[]" value="Python">
                    <label for="python">Python</label>
                </div>
                <div class="skill-option">
                    <input type="checkbox" id="java" name="skills[]" value="Java">
                    <label for="java">Java</label>
                </div>
                <div class="skill-option">
                    <input type="checkbox" id="cpp" name="skills[]" value="C++">
                    <label for="cpp">C++</label>
                </div>
                <div class="skill-option">
                    <input type="checkbox" id="javascript" name="skills[]" value="JavaScript">
                    <label for="javascript">JavaScript</label>
                </div>
                <div class="skill-option">
                    <input type="checkbox" id="sql" name="skills[]" value="SQL">
                    <label for="sql">SQL</label>
                </div>
                <div class="skill-option">
                    <input type="checkbox" id="flutter" name="skills[]" value="Flutter">
                    <label for="flutter">Flutter</label>
                </div>
                <div class="skill-option">
                    <input type="checkbox" id="fullstack" name="skills[]" value="Full Stack Developer">
                    <label for="fullstack">Full Stack</label>
                </div>
                <div class="skill-option">
                    <input type="checkbox" id="backend" name="skills[]" value="Backend Developer">
                    <label for="backend">Backend</label>
                </div>
                <div class="skill-option">
                    <input type="checkbox" id="bugbounty" name="skills[]" value="Bug Bounty">
                    <label for="bugbounty">Bug Bounty</label>
                </div>
                <div class="skill-option">
                    <input type="checkbox" id="gamedev" name="skills[]" value="Game Developer">
                    <label for="gamedev">Game Dev</label>
                </div>
            </div>
        </div>
        
        <input type="submit" value="Submit">
    </form>
</div>

</body>
</html>
