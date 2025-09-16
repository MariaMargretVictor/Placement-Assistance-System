<?php
include "placement/connection.php";
session_start();

$candidate_id = $_SESSION['candidate_id']; 
$profile_query = "SELECT * FROM candidate WHERE candidate_id = '$candidate_id'";
$profile_result = mysqli_query($conn, $profile_query);
$candidate = mysqli_fetch_assoc($profile_result);



?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Candidate Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <style>
    body {
      height: 100vh;
      width: 100vw;
      box-sizing: border-box;
    }
    .navbar {
      height: 10vh;
      background-color: rgb(26, 69, 198) !important;
    }
    .nav-link {
      font-weight: 600 !important;
      color: white !important;
      font-size: 20px;
      padding-right: 20px !important;
    }
    .navbar-brand {
      font-weight: 700;
      font-size: 28px;
      margin-left: 20px;
    }
    .content .container-fluid {
      height: auto;
      width: 100vw !important;
      margin-left: 0px !important;
      margin-right: 0px !important;
      padding: 0px !important;
      display: flex;
      flex-direction: column;
      align-items: center;
    }
    .company .card-img {
      height: 90vh !important;
      width: 100vw !important;
      object-fit: cover;
      filter: brightness(30%);
    }
    .company h5 {
      font-size: 48px;
      font-weight: 600;
      color: black;
    }
    .company h3 {
      font-size: 64px;
      font-weight: 700;
      color: black;
    }
    .company p {
      font-size: 16px;
      font-weight: 500;
      color: white;
    }
    .f-job a {
      font-weight: 700;
      font-size: 18px;
      width: 9rem;
    }
    .card-header {
      background: linear-gradient(135deg, #56a7d6, #005baa);
    }
    .card-body p {
      font-size: 18px;
      margin-bottom: 10px;
    }
  </style>
</head>
<body>

  
  <nav class="navbar navbar-expand-lg bg-body-tertiary sticky-top">
    <div class="container-fluid">
      <a class="navbar-brand text-white" href="#">Placement Assistance System</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
        data-bs-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup"
        aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
        <div class="navbar-nav ms-md-auto mx-5 px-4">
          <a class="nav-link active" aria-current="page" href="/placement/cadsh/candidate_dash.php">Home</a>
          <a class="nav-link" href="/placement/cadsh/jobpostings.php">Job Postings</a>
          <a class="nav-link" href="/placement/cadsh/checkapp.php">Check Application</a>
          <a class="nav-link" href="/placement/login/cand.php">Log Out</a>
        </div>
      </div>
    </div>
  </nav>


<section class="content">
    <div class="text-center">
      <h1 class="mb-3">Welcome</h1>
      <h2 class="text-primary fw-bold">
        <?php echo htmlspecialchars($candidate['c_name'] ?? ''); ?>
      </h2>
    </div>
  </div>
</section>

      
      <div class="container my-5">
        <div class="row justify-content-center">
          <div class="col-md-6">
            <div class="card shadow-lg rounded-4 border-0 bg-light">
              <div class="card-header text-white text-center rounded-top-4">
                <h4 class="mb-0">Candidate Profile</h4>
              </div>
              <div class="card-body px-4 py-3">
                <p><strong>Name:</strong> <?php echo htmlspecialchars($candidate['c_name']); ?></p>
                <p><strong>Email:</strong> <?php echo htmlspecialchars($candidate['c_email']); ?></p>
                <p><strong>Skills:</strong> <?php echo htmlspecialchars($candidate['c_skills']); ?></p>
                <p><strong>CGPA:</strong> <?php echo htmlspecialchars($candidate['c_cgpa']); ?></p>
                
                  
                  
                </p>
              </div>
            </div>
          </div>
        </div>
      </div>

    </div>
  </section>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>