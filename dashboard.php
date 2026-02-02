<?php
session_start();
require_once "config/db.php";


if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

// Validate session user from database
$user_id = $_SESSION['user_id'];

$stmt = $conn->prepare("SELECT id, first_name, last_name, email, role FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    session_unset();
    session_destroy();
    header("Location: index.php");
    exit();
}

$user = $result->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>Vet Clinic</title>

  <!-- Favicons -->
  <link href="assets/img/favicon.png" rel="icon">
  <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">

  <!-- Fonts -->
  <link href="https://fonts.googleapis.com" rel="preconnect">
  <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Roboto&family=Poppins&family=Ubuntu&display=swap" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="assets/vendor/aos/aos.css" rel="stylesheet">
  <link href="assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
  <link href="assets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
  <link href="assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">

  <!-- Main CSS File -->
  <link href="assets/css/main.css" rel="stylesheet">
</head>

<body class="index-page">

<header id="header" class="header d-flex align-items-center fixed-top">
  <div class="container position-relative d-flex align-items-center justify-content-between">

    <a href="index.php" class="logo d-flex align-items-center me-auto me-xl-0">
      <h1 class="sitename">Vet<span>Clinics</span></h1>
    </a>

    <nav id="navmenu" class="navmenu">
      <ul>
        <li><a href="index.php" class="active">Home</a></li>
        <li><a href="about.php">About</a></li>
        <li><a href="departments.php">Clinic</a></li>
        <li><a href="services.php">Services</a></li>
        <li><a href="contact.php">Contact</a></li>
      </ul>
      <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
    </nav>

    <!-- ================= PROFILE DROPDOWN ================= -->
    <div class="dropdown">
      <a href="#" class="btn-getstarted d-flex align-items-center gap-2 dropdown-toggle"
         data-bs-toggle="dropdown" aria-expanded="false">
        <i class="bi bi-person-circle fs-5"></i>
        <span class="d-none d-md-inline">
          <?php echo htmlspecialchars($user['first_name']); ?>
        </span>
      </a>

      <ul class="dropdown-menu dropdown-menu-end">
        <li class="dropdown-header text-center">
          <strong>
            <?php echo htmlspecialchars($user['first_name'] . " " . $user['last_name']); ?>
          </strong><br>
          <small class="text-muted">
            <?php echo htmlspecialchars($user['email']); ?>
          </small>
        </li>

        <li><hr class="dropdown-divider"></li>

        <li>
          <a class="dropdown-item d-flex align-items-center gap-2" href="profile.php">
            <i class="bi bi-person"></i> Profile
          </a>
        </li>

        <?php if ($user['role'] === 'admin'): ?>
        <li>
          <a class="dropdown-item d-flex align-items-center gap-2" href="admin/dashboard.php">
            <i class="bi bi-speedometer2"></i> Admin Dashboard
          </a>
        </li>
        <?php endif; ?>

        <li><hr class="dropdown-divider"></li>

        <li>
          <a class="dropdown-item d-flex align-items-center gap-2 text-danger" href="logout.php">
            <i class="bi bi-box-arrow-right"></i> Logout
          </a>
        </li>
      </ul>
    </div>

  </div>
</header>

<main class="main">

  <!-- ================= HERO SECTION ================= -->
  <section id="hero" class="hero section">
    <div class="container">
      <div class="row align-items-center">

        <div class="col-lg-5">
          <div class="hero-image" data-aos="fade-right">
            <img src="assets/img/health/staff8.jpg" class="img-fluid main-image">
          </div>
        </div>

        <div class="col-lg-7">
          <div class="hero-content" data-aos="fade-left">
            <span class="hero-badge">Trusted Veterinary Care</span>
            <h1 class="hero-title">Compassionate Vet Clinics for Your Pets</h1>
            <p class="hero-description">
              Providing professional and compassionate veterinary care for pets of all kinds.
            </p>
          </div>
        </div>

      </div>
    </div>
  </section>

  <!-- REST OF YOUR CONTENT REMAINS UNCHANGED -->

</main>

<!-- ================= FOOTER ================= -->
<div class="container footer-top">
  <div class="row gy-4">
    <div class="col-lg-4 footer-about">
      <span class="sitename">VetClinics</span>
      <p>123 PawCare Avenue<br>New York, NY 10001</p>
    </div>
  </div>
</div>

<!-- Scroll Top -->
<a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center">
  <i class="bi bi-arrow-up-short"></i>
</a>

<!-- Vendor JS Files -->
<script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="assets/vendor/aos/aos.js"></script>
<script src="assets/vendor/glightbox/js/glightbox.min.js"></script>
<script src="assets/vendor/swiper/swiper-bundle.min.js"></script>

<!-- Main JS -->
<script src="assets/js/main.js"></script>

</body>
</html>
