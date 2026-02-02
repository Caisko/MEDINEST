<?php
session_start();
$login_error = $_SESSION['login_error'] ?? '';
unset($_SESSION['login_error']);
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>Vet Clinic</title>
  <meta name="description" content="">
  <meta name="keywords" content="">

  <!-- Favicons -->
  <link href="assets/img/favicon.png" rel="icon">
  <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">

  <!-- Fonts -->
  <link href="https://fonts.googleapis.com" rel="preconnect">
  <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Ubuntu:ital,wght@0,300;0,400;0,500;0,700;1,300;1,400;1,500;1,700&display=swap" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="assets/vendor/aos/aos.css" rel="stylesheet">
  <link href="assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
  <link href="assets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
  <link href="assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

  <!-- Main CSS File -->
  <link href="assets/css/main.css" rel="stylesheet">

  <!-- =======================================================
  * Template Name: MediNest
  * Template URL: https://bootstrapmade.com/medinest-bootstrap-hospital-template/
  * Updated: Aug 11 2025 with Bootstrap v5.3.7
  * Author: BootstrapMade.com
  * License: https://bootstrapmade.com/license/
  ======================================================== -->
</head>
<body class="index-page">

  <header id="header" class="header d-flex align-items-center fixed-top">
    <div class="container position-relative d-flex align-items-center justify-content-between">

      <a href="index.php" class="logo d-flex align-items-center me-auto me-xl-0">
        <!-- Uncomment the line below if you also wish to use an image logo -->
        <!-- <img src="assets/img/logo.webp" alt=""> -->
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
<a class="btn-getstarted" href="#" data-bs-toggle="modal" data-bs-target="#loginModal">
  Log in
</a>

    </div>
  </header>
<!-- Login Modal -->
<div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title" id="loginModalLabel">Log In</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

        <div class="modal-body">
        <?php if($login_error): ?>
            <div class="alert alert-danger"><?php echo $login_error; ?></div>
        <?php endif; ?>

        <form id="loginForm" method="POST" action="login_process.php" novalidate>
          <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" class="form-control" name="email" placeholder="Enter email" required>
          </div>

        <div class="mb-3">
          <label class="form-label">Password</label>
          <input type="password" class="form-control" name="password" placeholder="Enter password" required>
        </div>

          <button type="submit" class="btn btn-primary w-100">Log In</button>

          <p class="text-center mt-3 mb-0">
            Don't have an account? <a href="register.php">Register here</a>
          </p>
        </form>
          </div>
    </div>
  </div>
</div>


  <main class="main">

    <!-- Hero Section -->
    <section id="hero" class="hero section">
      <div class="container">
        <div class="row align-items-center">
          <div class="col-lg-5">
            <div class="hero-image" data-aos="fade-right" data-aos-delay="100">
              <img src="assets/img/health/staff8.jpg" alt="Healthcare Professional" class="img-fluid main-image">
              <div class="floating-card emergency-card" data-aos="fade-up" data-aos-delay="300">
                <div class="card-content">
                  <i class="bi bi-telephone-fill"></i>
                  <div class="text">
                    <span class="label">24/7 Emergency</span>
                   
                  </div>
                </div>
              </div>
              <div data-aos="fade-up" data-aos-delay="400">
                  
              </div>
            </div>
          </div>

           <div class="col-lg-7">
            <div class="hero-content" data-aos="fade-left" data-aos-delay="200">
            <div class="badge-container">
              <span class="hero-badge">Trusted Veterinary Care</span>
            </div>

            <h1 class="hero-title">Compassionate Vet Clinics for Your Pets</h1>
            <p class="hero-description">
              Providing professional and compassionate veterinary care for pets of all kinds.
              Our clinics are dedicated to keeping your animals healthy, happy, and safe with
              modern facilities and experienced veterinarians.
            </p>


              

            
            </div>
          </div>
        </div>
      </div>

      <div class="background-elements">
        <div class="bg-shape shape-1"></div>
        <div class="bg-shape shape-2"></div>
        <div class="bg-pattern"></div>
      </div>
    </section><!-- /Hero Section -->

    <!-- Home About Section -->
    <section id="home-about" class="home-about section">

      <div class="container" data-aos="fade-up" data-aos-delay="100">

        <div class="row">
               <div class="col-lg-8 mx-auto text-center mb-5" data-aos="fade-up" data-aos-delay="150">
          <h2 class="section-heading">Excellence in Veterinary Care Since 1985</h2>
          <p class="lead-description">
            We are dedicated to providing top-quality veterinary services with compassion,
            expertise, and personalized care to ensure the health and happiness of your pets.
          </p>
        </div>
        </div>

        <div class="row align-items-center gy-5">
          <div class="col-lg-7" data-aos="fade-right" data-aos-delay="200">
            <div class="image-grid">
              <div class="primary-image">
                <img src="assets/img/health/facilities6.webp" alt="Modern hospital facility" class="img-fluid">
                <div class="certification-badge">
                  <i class="bi bi-award"></i>
                </div>
              </div>
              <div class="secondary-images">
                <div class="small-image">
                  <img src="assets/img/health/consultation3.webp" alt="Doctor consultation" class="img-fluid">
                </div>
                <div class="small-image">
                  <img src="assets/img/health/surgery2.webp" alt="Medical procedure" class="img-fluid">
                </div>
              </div>
            </div>
          </div>

          <div class="col-lg-5" data-aos="fade-left" data-aos-delay="300">
            <div class="content-wrapper">
              <div class="highlight-box">
                <div class="highlight-icon">
                  <i class="bi bi-heart-pulse-fill"></i>
                </div>
                  <div class="highlight-content">
                <h4>Pet-Centered Care</h4>
                <p>Each pet receives a personalized treatment plan tailored to their health, breed, age, and medical history, ensuring the best possible care.</p>
              </div>

              </div>

            <div class="feature-list">
              <div class="feature-item">
                <div class="feature-icon">
                  <i class="bi bi-check-circle-fill"></i>
                </div>
                <div class="feature-text">Advanced diagnostic tools and pet imaging</div>
              </div>
              <div class="feature-item">
                <div class="feature-icon">
                  <i class="bi bi-check-circle-fill"></i>
                </div>
                <div class="feature-text">Experienced and certified veterinarians</div>
              </div>
              <div class="feature-item">
                <div class="feature-icon">
                  <i class="bi bi-check-circle-fill"></i>
                </div>
                <div class="feature-text">Comprehensive rehabilitation and recovery care for pets</div>
              </div>
              <div class="feature-item">
                <div class="feature-icon">
                  <i class="bi bi-check-circle-fill"></i>
                </div>
                <div class="feature-text">24/7 emergency veterinary services</div>
              </div>
            </div>
      </div>

    </section><!-- /Home About Section -->

    <!-- Featured Departments Section -->
    <section id="featured-departments" class="featured-departments section">

      <!-- Section Title -->
      <div class="container section-title" data-aos="fade-up">
        <h2>Featured Clinics</h2>
        <p>Necessitatibus eius consequatur ex aliquid fuga eum quidem sint consectetur velit</p>
      </div><!-- End Section Title -->

      <div class="container" data-aos="fade-up" data-aos-delay="100">

        <div class="departments-showcase">

          <div class="featured-department" data-aos="fade-up" data-aos-delay="200">
            <div class="row align-items-center">
              <div class="col-lg-6 order-lg-1">
                <div class="department-content">
                  <div class="department-category">Veterinary Care</div>
                  <h2 class="department-title">24/7 Emergency Vet Clinics</h2>
                  <p class="department-description">
                    Our veterinary clinics provide round-the-clock emergency care for pets,
                    offering immediate medical attention, advanced treatment, and compassionate
                    support when your animals need it most.
                  </p>
                  <div class="department-features">
                    <div class="feature-item">
                      <i class="fas fa-check-circle"></i>
                      <span>24/7 Emergency Pet Care</span>
                    </div>
                    <div class="feature-item">
                      <i class="fas fa-check-circle"></i>
                      <span>Advanced Diagnostic & Treatment Services</span>
                    </div>
                    <div class="feature-item">
                      <i class="fas fa-check-circle"></i>
                      <span>Experienced & Licensed Veterinarians</span>
                    </div>
                  </div>
                  <a href="#" class="cta-link">Find a Vet Clinic <i class="fas fa-arrow-right"></i></a>
                </div>
              </div>

              <div class="col-lg-6 order-lg-2">
                <div class="department-visual">
                  <div class="image-wrapper">
                    <img src="assets/img/health/emergency.webp" alt="Emergency Department" class="img-fluid">
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="departments-grid">
            <div class="row">
              <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="300">
                <div class="department-card">
                  <div class="card-icon">
                    <i class="fas fa-heartbeat"></i>
                  </div>
                  <div class="card-content">
                    <h3 class="card-title">Experienced & Compassionate Veterinarians</h3>
                    <p class="card-description">Licensed professionals dedicated to providing gentle, high-quality care for all pets.</p>
                    <div class="card-stats">
                      <div class="stat-item">
                      </div>
                      <div class="stat-item">
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="350">
                <div class="department-card">
                  <div class="card-icon">
                    <i class="fas fa-brain"></i>
                  </div>
                  <div class="card-content">
                    <h3 class="card-title">Modern Diagnostic Equipment</h3>
                    <p class="card-description">Advanced tools for accurate and fast diagnosis, including laboratory testing and imaging.</p>
                    <div class="card-stats">
                      <div class="stat-item">
                      </div>
                      <div class="stat-item">
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="400">
                <div class="department-card">
                  <div class="card-icon">
                    <i class="fas fa-cut"></i>
                  </div>
                  <div class="card-content">
                    <h3 class="card-title">Comprehensive Pet Care Services</h3>
                    <p class="card-description">From wellness exams and vaccinations to surgery and grooming all in one clinic.</p>
                    <div class="card-stats">
                      <div class="stat-item">  
                      </div>
                      <div class="stat-item">
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="450">
                <div class="department-card">
                  <div class="card-icon">
                    <i class="fas fa-baby"></i>
                  </div>
                  <div class="card-content">
                    <h3 class="card-title">Clean, Safe & Pet-Friendly Facility</h3>
                    <p class="card-description">A hygienic, stress-free environment designed for the comfort of pets and their owners.</p>
                    <div class="card-stats">
                      <div class="stat-item">                        
                      </div>
                      <div class="stat-item">                        
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="500">
                <div class="department-card">
                  <div class="card-icon">
                    <i class="fas fa-eye"></i>
                  </div>
                  <div class="card-content">
                    <h3 class="card-title">Convenient Appointments & Emergency Support</h3>
                    <p class="card-description">Flexible scheduling with reliable care when your pet needs it most.</p>
                    <div class="card-stats">
                      <div class="stat-item">                       
                       </div>
                      <div class="stat-item">                       
                      </div>
                    </div>
                  </div>
                </div>
              </div>                
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
    <!-- Featured Services Section -->
    <section id="featured-services" class="featured-services section">

      <!-- Section Title -->
      <div class="container section-title" data-aos="fade-up">
        <h2>Featured Services</h2>
        <p>Necessitatibus eius consequatur ex aliquid fuga eum quidem sint consectetur velit</p>
      </div><!-- End Section Title -->

      <div class="container" data-aos="fade-up" data-aos-delay="100">

        <div class="row gy-4">

          <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="200">
            <div class="service-card">
              <div class="service-icon">
                <i class="fas fa-heartbeat"></i>
              </div>
              <div class="service-image">
                <img src="assets/img/health/generalcheckups.jpg" alt="Service" class="img-fluid" loading="lazy">
              </div>
              <div class="service-content">
                <h3>General Check-Ups & Consultations</h3>
                <p>Routine health examinations to assess your pet’s overall condition, detect early signs of illness, and provide health advice.</p>
                <a href="#" class="service-link">Learn More <i class="fas fa-arrow-right"></i></a>
              </div>
            </div>
          </div><!-- End Service Card -->

          <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="300">
            <div class="service-card">
              <div class="service-icon">
                <i class="fas fa-brain"></i>
              </div>
              <div class="service-image">
                <img src="assets/img/health/vaccination.jpg" alt="Service" class="img-fluid" loading="lazy">
              </div>
              <div class="service-content">
                <h3>Vaccination & Preventive Care</h3>
                <p>Core and non-core vaccinations, deworming, flea and tick control, and parasite prevention to keep pets healthy.</p>
                <a href="#" class="service-link">Learn More <i class="fas fa-arrow-right"></i></a>
              </div>
            </div>
          </div><!-- End Service Card -->

          <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="400">
            <div class="service-card">
              <div class="service-icon">
                <i class="fas fa-bone"></i>
              </div>
              <div class="service-image">
                <img src="assets/img/health/diagnostics.jpg" alt="Service" class="img-fluid" loading="lazy">
              </div>
              <div class="service-content">
                <h3>Diagnostic Services</h3>
                <p>Laboratory tests, blood work, fecal exams, urinalysis, and basic imaging (X-ray, ultrasound) to diagnose illnesses.</p>
                <a href="#" class="service-link">Learn More <i class="fas fa-arrow-right"></i></a>
              </div>
            </div>
          </div><!-- End Service Card -->

          <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="200">
            <div class="service-card">
              <div class="service-icon">
                <i class="fas fa-baby"></i>
              </div>
              <div class="service-image">
                <img src="assets/img/health/surgical.jpg" alt="Service" class="img-fluid" loading="lazy">
              </div>
              <div class="service-content">
                <h3>Surgical Procedures</h3>
                <p>Minor and major surgeries such as spaying/neutering, wound treatment, mass removal, and soft-tissue procedures.</p>
                <a href="#" class="service-link">Learn More <i class="fas fa-arrow-right"></i></a>
              </div>
            </div>
          </div><!-- End Service Card -->

          <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="300">
            <div class="service-card">
              <div class="service-icon">
                <i class="fas fa-ribbon"></i>
              </div>
              <div class="service-image">
                <img src="assets/img/health/grooming.jpg" alt="Service" class="img-fluid" loading="lazy">
              </div>
              <div class="service-content">
                <h3>Grooming & Basic Pet Care</h3>
                <p>Nail trimming, ear cleaning, bathing, and basic grooming to maintain hygiene and comfort.</p>
                <a href="#" class="service-link">Learn More <i class="fas fa-arrow-right"></i></a>
              </div>
            </div>
          </div><!-- End Service Card -->

          

        </div>

      </div>

    </section>
  </main>

 <div class="container footer-top">
  <div class="row gy-4">
    <div class="col-lg-4 col-md-6 footer-about">
      <a href="index.php" class="logo d-flex align-items-center">
        <span class="sitename">VetClinics</span>
      </a>
      <div class="footer-contact pt-3">
        <p>123 PawCare Avenue</p>
        <p>New York, NY 10001</p>
        <p class="mt-3"><strong>Phone:</strong> <span>+1 555 234 5678</span></p>
        <p><strong>Email:</strong> <span>contact@vetclinics.com</span></p>
      </div>
      <div class="social-links d-flex mt-4">
        <a href="#"><i class="bi bi-twitter-x"></i></a>
        <a href="#"><i class="bi bi-facebook"></i></a>
        <a href="#"><i class="bi bi-instagram"></i></a>
        <a href="#"><i class="bi bi-linkedin"></i></a>
      </div>
    </div>

    <div class="col-lg-2 col-md-3 footer-links">
      <h4>Quick Links</h4>
      <ul>
        <li><a href="#">Home</a></li>
        <li><a href="#">About Us</a></li>
        <li><a href="#">Our Vets</a></li>
        <li><a href="#">Appointments</a></li>
        <li><a href="#">Contact</a></li>
      </ul>
    </div>

    <div class="col-lg-2 col-md-3 footer-links">
      <h4>Our Services</h4>
      <ul>
        <li><a href="#">General Checkups</a></li>
        <li><a href="#">Vaccinations</a></li>
        <li><a href="#">Pet Surgery</a></li>
        <li><a href="#">Dental Care</a></li>
        <li><a href="#">Emergency Care</a></li>
      </ul>
    </div>

    <div class="col-lg-2 col-md-3 footer-links">
      <h4>Pet Care</h4>
      <ul>
        <li><a href="#">Nutrition Advice</a></li>
        <li><a href="#">Preventive Care</a></li>
        <li><a href="#">Parasite Control</a></li>
        <li><a href="#">Senior Pet Care</a></li>
        <li><a href="#">Puppy & Kitten Care</a></li>
      </ul>
    </div>

    <div class="col-lg-2 col-md-3 footer-links">
      <h4>Support</h4>
      <ul>
        <li><a href="#">FAQs</a></li>
        <li><a href="#">Pet Insurance</a></li>
        <li><a href="#">Client Resources</a></li>
        <li><a href="#">Terms of Service</a></li>
        <li><a href="#">Privacy Policy</a></li>
      </ul>
    </div>

  </div>
</div>


  <!-- Scroll Top -->
  <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <!-- Preloader -->
  <div id="preloader"></div>

  <!-- Vendor JS Files -->
  <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="assets/vendor/php-email-form/validate.js"></script>
  <script src="assets/vendor/aos/aos.js"></script>
  <script src="assets/vendor/glightbox/js/glightbox.min.js"></script>
  <script src="assets/vendor/purecounter/purecounter_vanilla.js"></script>
  <script src="assets/vendor/imagesloaded/imagesloaded.pkgd.min.js"></script>
  <script src="assets/vendor/isotope-layout/isotope.pkgd.min.js"></script>
  <script src="assets/vendor/swiper/swiper-bundle.min.js"></script>

  <!-- Main JS File -->
  <script src="assets/js/main.js"></script>

</body>

</html>