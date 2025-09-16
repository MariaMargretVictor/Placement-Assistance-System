<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Company Form</title>
    <style>
        
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: Arial, Helvetica, sans-serif;
}


body {
    background: url('/placement/assets/4565.jpg') no-repeat center center/cover;
    height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
}


.container {
    background: rgba(255, 255, 255, 0.95);
    padding: 30px;
    width: 400px;
    border-radius: 10px;
    box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
}


.form-box {
    display: flex;
    flex-direction: column;
}


h2 {
    text-align: center;
    margin-bottom: 20px;
    font-weight: 600;
}

label {
    font-weight: 400;
    margin: 8px 0 4px;
}

input[type="text"] {
    width: 100%;
    padding: 2px;
    font-size: 16px;
    margin-bottom: 10px;
    border: 1px solid #ccc;
    border-radius: 5px;
}
input{
    height: 35px;
    width: 100%;
    /* padding: 10px; */
    margin-top: 5px;
    border: 1px solid #ccc;
    border-radius: 5px;
}

/* File Upload */
input[type="file"] {
    margin-bottom: 15px;
}


button {
    background-color: #4CAF50;
    color: white;
    border: none;
    padding: 10px;
    border-radius: 5px;
    cursor: pointer;
    font-size: 16px;
    transition: 0.3s;
}

button:hover {
    background-color: #45a049;
}

    </style>
</head>
<body>
    <div class="container">
        <form class="form-box" action="company.php" method="POST" enctype="multipart/form-data">
            <h2>Company Information</h2>

            <label for="company_name">Company Name:</label>
            <input type="text" id="company_name" name="company_name" required>

            <label for="pass">Password:</label>
            <input type="password" id="pass" name="company_password" required>


            <label for="address">Address:</label>
            <input type="text" id="address" name="address" required>

            <label for="contact_no">Contact No:</label>
            <input type="text" id="contact_no" name="contact_no" required>

            <label for="logo">Company Logo:</label>
            <input type="file" id="logo" name="logo" accept="image/*" required>

            <button type="submit">Submit</button>
        </form>
    </div>

    <?php

include 'placement/connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $company_name = $_POST['company_name'];
    $company_password = $_POST['company_password'];
    $company_address = $_POST['address'];
    $company_contactno = $_POST['contact_no'];

    $hashed_password = password_hash($company_password, PASSWORD_DEFAULT);

    $target_dir = $_SERVER['DOCUMENT_ROOT'] . "/placement/uploads/";
    $logo_name = basename($_FILES["logo"]["name"]);
    $target_file = $target_dir . $logo_name;
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    $check = getimagesize($_FILES["logo"]["tmp_name"]);
    if ($check !== false) {
        $uploadOk = 1;
    } else {
        echo "<p>File is not an image.</p>";
        $uploadOk = 0;
    }

    $allowed_types = array("jpg", "jpeg", "png", "gif");
    if (!in_array($imageFileType, $allowed_types)) {
        echo "<p>Sorry, only JPG, JPEG, PNG & GIF files are allowed.</p>";
        $uploadOk = 0;
    }

    if ($uploadOk == 1) {
        if (move_uploaded_file($_FILES["logo"]["tmp_name"], $target_file)) {
            $sql = "INSERT INTO company (company_name, company_password, company_address, company_contactno, company_logo) VALUES (?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssss", $company_name, $hashed_password, $company_address, $company_contactno, $logo_name);

            if ($stmt->execute()) {
                echo "<p>Company information saved successfully.</p>";
                header("Location: /placement/login/comp.php");
                exit();
            } else {
                echo "<p>Error: " . $stmt->error . "</p>";
            }
        } else {
            echo "<p>Sorry, there was an error uploading your file.</p>";
        }
    }
}
?>

</body>
</html>