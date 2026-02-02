<?php
// Enable error reporting to debug white screen issues
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Start session for login/profile dropdown (matches index.php)
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>About Us | VetCare Clinic</title>

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">

  <!-- Bootstrap Icons -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

  <style>
    body {
      font-family: 'Roboto', sans-serif;
      line-height: 1.6;
      color: #333;
    }

    .hero {
      background: url('https://images.unsplash.com/photo-1601758123927-3f9e9b95ab5b?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=MnwxfDB8MXxyYW5kb218MHx8dmV0fGNsaW5pfHx8fHx8MTY5NzkyNjk2Mg&ixlib=rb-4.0.3&q=80&w=1920') no-repeat center center/cover;
      height: 50vh;
      display: flex;
      align-items: center;
      justify-content: center;
      color: #fff;
      text-shadow: 2px 2px 8px rgba(0,0,0,0.6);
    }

    .hero h1 {
      font-size: 3rem;
      font-weight: 700;
    }

    .section-title {
      margin-bottom: 2rem;
      font-weight: 700;
      color: #007bff;
    }

    .team-member img {
      border-radius: 50%;
      margin-bottom: 1rem;
    }

    .services i {
      font-size: 2rem;
      color: #007bff;
      margin-bottom: 0.5rem;
    }

    .footer {
      background-color: #f8f9fa;
      padding: 2rem 0;
      margin-top: 4rem;
    }

    .facility-img {
      width: 100%;
      border-radius: 8px;
      margin-bottom: 1rem;
    }
  </style>
</head>
<body>

  <!-- Navbar (matches index.php) -->
  <nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm">
    <div class="container">
      <a class="navbar-brand fw-bold" href="index.php">VetCare Clinic</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
        aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ms-auto">
          <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
          <li class="nav-item"><a class="nav-link active" href="about.php">About Us</a></li>
          <li class="nav-item"><a class="nav-link" href="services.php">Services</a></li>
          <li class="nav-item"><a class="nav-link" href="contact.php">Contact</a></li>

          <?php if(isset($_SESSION['fullname'])): ?>
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" href="#" id="profileDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                <?php echo $_SESSION['fullname']; ?>
              </a>
              <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="profileDropdown">
                <li><a class="dropdown-item" href="profile.php">Profile</a></li>
                <li><a class="dropdown-item" href="logout.php">Logout</a></li>
              </ul>
            </li>
          <?php else: ?>
            <li class="nav-item"><a class="nav-link" href="login.php">Login</a></li>
          <?php endif; ?>

        </ul>
      </div>
    </div>
  </nav>

  <!-- Hero Section -->
  <section class="hero">
    <h1>About VetCare Clinic</h1>
  </section>

  <!-- About Section -->
  <section class="container py-5">
    <div class="row">
      <div class="col-md-6">
        <h2 class="section-title">Who We Are</h2>
        <p>VetCare Clinic is a premier veterinary hospital dedicated to providing high-quality medical care and compassionate support for pets and their families. Established in 2010, we have been serving the local community for over a decade, combining advanced veterinary medicine with a loving, family-oriented approach.</p>
        <p>Our goal is to ensure that every animal receives personalized attention and the best treatment possible, while providing guidance and education to pet owners for long-term health and wellness.</p>
      </div>
      <div class="col-md-6">
        <img src="https://images.unsplash.com/photo-1583337130417-62d216d7e63b?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&q=80&w=800" class="img-fluid rounded" alt="Veterinarian with pet">
      </div>
    </div>
  </section>

  <!-- Mission & Vision -->
  <section class="bg-light py-5">
    <div class="container text-center">
      <h2 class="section-title">Our Mission & Vision</h2>
      <div class="row">
        <div class="col-md-6 mb-4">
          <h4>Our Mission</h4>
          <p>To provide compassionate, expert veterinary care for all pets while educating and supporting their families to ensure lifelong health and happiness.</p>
        </div>
        <div class="col-md-6 mb-4">
          <h4>Our Vision</h4>
          <p>To be recognized as a leading veterinary clinic in the community, known for excellent medical care, innovative treatments, and genuine love for animals.</p>
        </div>
      </div>
    </div>
  </section>

  <!-- Services -->
  <section class="container py-5">
    <h2 class="section-title text-center">Our Services</h2>
    <div class="row text-center services">
      <div class="col-md-4 mb-4">
        <i class="bi bi-heart-pulse-fill"></i>
        <h5>Preventive Care</h5>
        <p>Routine check-ups, vaccinations, and wellness exams to keep your pets healthy.</p>
      </div>
      <div class="col-md-4 mb-4">
        <i class="bi bi-thermometer-half"></i>
        <h5>Medical Treatment</h5>
        <p>Diagnosis and treatment for illnesses, injuries, and chronic conditions.</p>
      </div>
      <div class="col-md-4 mb-4">
        <i class="bi bi-scissors"></i>
        <h5>Surgery & Dentistry</h5>
        <p>Safe surgical procedures, dental care, and post-operative support.</p>
      </div>
    </div>
  </section>

  <!-- Team -->
  <section class="bg-light py-5">
    <div class="container">
      <h2 class="section-title text-center">Meet Our Team</h2>
      <div class="row text-center">
        <div class="col-md-3 mb-4 team-member">
          <img src="https://images.unsplash.com/photo-1601758123927-3f9e9b95ab5b?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&q=80&w=200" alt="Dr. John Smith" class="img-fluid">
          <h6>Dr. John Smith</h6>
          <p>Veterinarian & Founder</p>
        </div>
        <div class="col-md-3 mb-4 team-member">
          <img src="https://images.unsplash.com/photo-1599058917211-556aef63b157?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&q=80&w=200" alt="Dr. Sarah Lee" class="img-fluid">
          <h6>Dr. Sarah Lee</h6>
          <p>Senior Veterinarian</p>
        </div>
        <div class="col-md-3 mb-4 team-member">
          <img src="https://images.unsplash.com/photo-1598514982323-1f0d912f3b83?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&q=80&w=200" alt="Nurse Emma" class="img-fluid">
          <h6>Emma Johnson</h6>
          <p>Veterinary Nurse</p>
        </div>
        <div class="col-md-3 mb-4 team-member">
          <img src="https://images.unsplash.com/photo-1595433562696-2f0530fa4f4b?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&q=80&w=200" alt="Receptionist Mia" class="img-fluid">
          <h6>Mia Brown</h6>
          <p>Reception & Customer Care</p>
        </div>
      </div>
    </div>
  </section>

  <!-- Facilities -->
  <section class="container py-5">
    <h2 class="section-title text-center">Our Facilities</h2>
    <div class="row">
      <div class="col-md-4 mb-4">
        <img src="https://images.unsplash.com/photo-1588776814546-882fa7e6f29b?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&q=80&w=400" alt="Exam Room" class="facility-img">
        <h6>State-of-the-Art Exam Rooms</h6>
      </div>
      <div class="col-md-4 mb-4">
        <img src="https://images.unsplash.com/photo-1603791440384-56cd371ee9a7?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&q=80&w=400" alt="Surgery Room" class="facility-img">
        <h6>Advanced Surgery & Recovery</h6>
      </div>
      <div class="col-md-4 mb-4">
        <img src="https://images.unsplash.com/photo-1599058917211-556aef63b157?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&q=80&w=400" alt="Pet Boarding" class="facility-img">
        <h6>Comfortable Boarding & Grooming</h6>
      </div>
    </div>
  </section>

  <!-- Footer -->
  <footer class="footer">
    <div class="container text-center">
      <p>&copy; 2026 VetCare Clinic. All Rights Reserved.</p>
      <p>Email: info@vetcareclinic.com | Phone: +63 912 345 6789 | Address: 123 Pet Street, Manila, Philippines</p>
    </div>
  </footer>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
