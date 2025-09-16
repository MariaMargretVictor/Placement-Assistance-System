<?php

session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Placement Assistance System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
        body {
            height: 100vh;
            width: 100vw;
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
        .d-block {
            height: 90vh;
            object-fit: cover;
            filter: brightness(30%);
        }
        .carousel-caption {
            margin-bottom: 16rem;
        }
        .carousel-caption h5 {
            font-size: 50px;
            font-weight: 700;
            color: white;
        }
        .carousel-caption p {
            color: whitesmoke;
            font-weight: 700;
            font-size: 24px;
        }
        .carousel-caption span {
            color: rgb(26, 69, 198) !important;
        }
        .carousel-caption a {
            margin-left: 2rem;
            width: 8rem !important;
        }
        
        .active-link {
         color: gold !important;   
         text-decoration:  solid; 
         transition: all 0.3s ease-in-out;
        }


    .click-effect {
    transform: scale(0.9);  
    background-color: rgba(255, 255, 255, 0.2); 
    transition: all 0.2s ease-in-out; 
    }
    </style>
</head>
<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg bg-body-tertiary sticky-top">
        <div class="container-fluid">
            <a class="navbar-brand text-white" href="web.php">Placement Assistance System</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
                <div class="navbar-nav ms-md-auto mx-5 px-4">
                    <a class="nav-link active" aria-current="page" href="web.php">Home</a>
                    <a class="nav-link" href="/placement/login/comp.php">Company</a>
                    <a class="nav-link" href="/placement/login/cand.php">Candidate</a>
                    <a class="nav-link" href="/placement/admin/admin2.php">Admin</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Content -->
    <section id="main-container">
        <div class="container-fluid p-0">
            <div id="carouselExampleIndicators" class="carousel slide">
                <div class="carousel-indicators">
                    <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
                    <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="1" aria-label="Slide 2"></button>
                    <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="2" aria-label="Slide 3"></button>
                </div>
                <div class="carousel-inner">
                    <div class="carousel-item active">
                        <img src="/placement/home/image_1.jpeg" class="d-block w-100" alt="...">
                        <div class="carousel-caption d-none d-md-block">
                            <h5>Your <span>Future</span> Starts Here</h5>
                            <p>"Success is where preparation and opportunity meet." – Bobby Unser</p>
                            <p class="d-inline-flex gap-1 buttons">
                                <a href="/placement/login/cand.php" class="btn btn-primary" role="button">Log In</a>
                                <a href="/placement/candidate/candidate.php" class="btn btn-outline-light" role="button">Sign Up</a>
                            </p>
                        </div>
                    </div>
                    <div class="carousel-item">
                        <img src="/placement/home/image_2.jpeg" class="d-block w-100" alt="...">
                        <div class="carousel-caption d-none d-md-block">
                            <h5>Unlock Your <span>Potential</span></h5>
                            <p>"The only limit to our realization of tomorrow is our doubts of today." – Franklin D. Roosevelt</p>
                            <p class="d-inline-flex gap-1">
                                <a href="/placement/login/cand.php" class="btn btn-primary" role="button">Log In</a>
                                <a href="/placement/candidate/candidate.php" class="btn btn-outline-light" role="button">Sign Up</a>
                            </p>
                        </div>
                    </div>
                    <div class="carousel-item">
                        <img src="/placement/home/image_3.jpeg" class="d-block w-100" alt="...">
                        <div class="carousel-caption d-none d-md-block">
                            <h5>Ace Your <span>Placements</span></h5>
                            <p>"Opportunities don't happen, you create them." – Chris Grosser</p>
                            <p class="d-inline-flex gap-1">
                                <a href="/placement/login/cand.php" class="btn btn-primary" role="button">Log In</a>
                                <a href="/placement/candidate/candidate.php" class="btn btn-outline-light" role="button">Sign Up</a>
                            </p>
                        </div>
                    </div>
                </div>
                <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Previous</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Next</span>
                </button>
            </div>
        </div>
    </section>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script>
    document.addEventListener("DOMContentLoaded", function() {
        let navLinks = document.querySelectorAll(".nav-link"); 
        
        navLinks.forEach(link => {
            link.addEventListener("click", function() {
                
                navLinks.forEach(nav => nav.classList.remove("active-link"));

                
                this.classList.add("active-link");
            });
        });
    });
</script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        let navLinks = document.querySelectorAll(".nav-link"); 

        navLinks.forEach(link => {
            link.addEventListener("click", function (event) {
                
                this.classList.add("click-effect");

                
                setTimeout(() => {
                    this.classList.remove("click-effect");
                }, 200);
            });

            
            link.addEventListener("touchstart", function (event) {
                this.classList.add("click-effect");

                setTimeout(() => {
                    this.classList.remove("click-effect");
                }, 200);
            });
        });
    });
</script>
</body>
</html>