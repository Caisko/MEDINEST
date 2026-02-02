<?php
session_start();
require_once "config/db.php"; // Use your existing DB connection

// Redirect to login if not logged in
if(!isset($_SESSION['user_id'])){
    header("Location: index.php");
    exit();
}

// Get user info
$user_id = $_SESSION['user_id'];
$sql = "SELECT * FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
?>


<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Vet Clinic - User Dashboard</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="assets/img/favicon.png" rel="icon">
<style>
body {
    background-color: #f8f9fa;
}
.sidebar {
    min-height: 100vh;
    background-color: #343a40;
    color: white;
}
.sidebar a {
    color: white;
    text-decoration: none;
}
.sidebar a:hover {
    background-color: #495057;
}
.main-content {
    padding: 20px;
}
.card {
    margin-bottom: 20px;
}
</style>
</head>
<body>
<div class="container-fluid">
  <div class="row">
    <!-- Sidebar -->
    <nav class="col-md-3 col-lg-2 d-md-block sidebar collapse">
      <div class="position-sticky pt-3">
        <h3 class="text-center py-3">Vet Clinic</h3>
        <ul class="nav flex-column">
          <li class="nav-item">
            <a class="nav-link active" href="dashboard.php">Dashboard</a>
          </li>
         <li class="nav-item">
            <a class="nav-link" href="pet.php">My pets</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="appointments.php">My Appointments</a>
          </li>
            <li class="nav-item">
            <a class="nav-link" href="messages.php">Message</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="index.php">Home</a>
          </li>
          <li class="nav-item">
            <a class="nav-link text-danger" href="logout.php">Logout</a>
          </li>
        </ul>
      </div>
    </nav>

    <!-- Main Content -->
    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 main-content">
      <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Welcome, <?php echo htmlspecialchars($user['first_name']); ?>!</h1>
      </div>

      <!-- Profile Card -->
      <div class="card">
        <div class="card-header">
          My Profile
        </div>
        <div class="card-body">
          <p><strong>Full Name:</strong> <?php echo htmlspecialchars($user['first_name'] . ' ' . $user['middle_name'] . ' ' . $user['last_name']); ?></p>
          <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
          <p><strong>Contact:</strong> <?php echo htmlspecialchars($user['contact']); ?></p>
          <p><strong>Address:</strong> <?php echo htmlspecialchars($user['address']); ?></p>
        </div>
      </div>

      <!-- Appointments Card -->
      <div class="card">
        <div class="card-header">
          Upcoming Appointments
        </div>
        <div class="card-body">
          <p>No upcoming appointments yet. <a href="appointments.php">Book Now</a></p>
        </div>
      </div>
    </main>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
