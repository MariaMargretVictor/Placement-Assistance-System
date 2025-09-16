
session_start();
include 'placement/connection.php'; // Adjust path if needed

// Check if candidate is logged in
if (!isset($_SESSION['c_email'])) {
    header("Location: /placement/login/cand.php");
    exit();
}

$c_email = $_SESSION['c_email'];

// Handle profile update
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $c_cgpa = $_POST['c_cgpa'];
    $c_skills = $_POST['c_skills'];

    $updateQuery = "UPDATE candidate SET c_cgpa='$c_cgpa', c_skills='$c_skills' WHERE c_email='$c_email'";
    mysqli_query($conn, $updateQuery);
}

// Fetch candidate details
$query = "SELECT * FROM candidate WHERE c_email='$c_email'";
$result = mysqli_query($conn, $query);
$candidate = mysqli_fetch_assoc($result);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Candidate Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      height: 100vh;
      box-sizing: border-box;
      background-color: #f8f9fa;
    }
    .navbar {
      height: 10vh;
      background-color: rgb(26, 69, 198) !important;
    }
    .nav-link {
      font-weight: 600;
      color: white !important;
      font-size: 20px;
      padding-right: 20px;
    }
    .navbar-brand {
      font-weight: 700;
      font-size: 28px;
      margin-left: 20px;
    }
    .hero-section {
      position: relative;
      height: 60vh;
      overflow: hidden;
    }
    .hero-section img {
      height: 100%;
      width: 100%;
      object-fit: cover;
      filter: brightness(40%);
    }
    .hero-content {
      position: absolute;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      text-align: center;
      color: black;
    }
    .hero-content h3 {
      font-size: 3rem;
      font-weight: 700;
    }
    .card-profile {
      max-width: 500px;
      margin: -50px auto 0;
      background: white;
      box-shadow: 0 0 20px rgba(0,0,0,0.1);
      border-radius: 20px;
      padding: 2rem;
    }
    .profile-img {
      width: 100px;
      height: 100px;
      object-fit: contain;
      margin: 0 auto 1rem;
    }
    .form-control {
      margin-bottom: 10px;
    }
    .btn-edit {
      background-color: #1a45c6;
      color: white;
      font-weight: 600;
    }
  </style>
</head>
<body>

<nav class="navbar navbar-expand-lg sticky-top">
  <div class="container-fluid">
    <a class="navbar-brand text-white" href="#">Placement Assistance System</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
      data-bs-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false"
      aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
      <div class="navbar-nav ms-md-auto mx-5 px-4">
        <a class="nav-link active" href="#">Home</a>
        <a class="nav-link" href="/placement/cadsh/jobpostings.php">Job Postings</a>
        <a class="nav-link" href="/placement/cadsh/checkapp.php">Check Application</a>
        <a class="nav-link" href="/placement/login/cand.php">Log Out</a>
      </div>
    </div>
  </div>
</nav>

<!-- Hero Section -->
<section class="hero-section">
  <img src="IMG-20250314-WA0015[1].jpg" alt="">
  <div class="hero-content">
    <h5>Welcome</h5>
    <h3><?php echo htmlspecialchars($candidate['c_name'] ?? ''); ?></h3>
    
  </div>
</section>

<!-- Profile Card -->
<div class="card-profile mt-5">
  <form method="POST">
    <img src="account_circle_70dp_E8EAED_FILL0_wght400_GRAD0_opsz48.png" class="profile-img" alt="Profile Icon">
    <h4 class="text-center mb-3"><?php echo htmlspecialchars($candidate['c_name'] ?? ''); ?></h4>
    
    <div class="mb-3">
      <label class="form-label">Email:</label>
      <input type="email" class="form-control" value="<?php echo htmlspecialchars($candidate['c_email'] ?? ''); ?>" readonly>
    </div>

    <div class="mb-3">
      <label class="form-label">CGPA:</label>
      <input type="text" name="c_cgpa" class="form-control" value="<?php echo htmlspecialchars($candidate['c_cgpa'] ?? ''); ?>">
    </div>

    <div class="mb-3">
      <label class="form-label">Skills:</label>
      <input type="text" name="c_skills" class="form-control" value="<?php echo htmlspecialchars($candidate['c_skills'] ?? ''); ?>">
    </div>

    <button type="submit" class="btn btn-edit w-100">Update Profile</button>
  </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>