<?php
session_start();
require_once "../config/db.php"; // Your DB connection

$errors = [];
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Collect form inputs
    $clinic_name = $_POST['clinic_name'] ?? '';
    $first_name  = $_POST['first_name'] ?? '';
    $last_name   = $_POST['last_name'] ?? '';
    $contact     = $_POST['contact'] ?? '';
    $address     = $_POST['address'] ?? '';
    $lat         = $_POST['latitude'] ?? '';
    $lng         = $_POST['longitude'] ?? '';
    $admin_email = $_POST['admin_email'] ?? '';
    $password    = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    // Validate passwords
    if ($password !== $confirm_password) {
        $errors[] = "Passwords do not match.";
    }

    // Handle file uploads
    $uploads_dir = "../uploads";
    if (!file_exists($uploads_dir)) mkdir($uploads_dir, 0777, true);

    $verification_file = $_FILES['verification_doc']['name'] ?? '';
    $id_file           = $_FILES['id_verification']['name'] ?? '';
    $face_file         = $_FILES['face_recognition']['name'] ?? '';

    $verification_path = $id_path = $face_path = '';

    if (empty($errors)) {
        if ($verification_file) {
            $verification_path = $uploads_dir . "/" . time() . "_" . basename($verification_file);
            move_uploaded_file($_FILES['verification_doc']['tmp_name'], $verification_path);
        }
        if ($id_file) {
            $id_path = $uploads_dir . "/" . time() . "_" . basename($id_file);
            move_uploaded_file($_FILES['id_verification']['tmp_name'], $id_path);
        }
        if ($face_file) {
            $face_path = $uploads_dir . "/" . time() . "_" . basename($face_file);
            move_uploaded_file($_FILES['face_recognition']['tmp_name'], $face_path);
        }

        // Hash password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Insert into database
        $stmt = $conn->prepare("INSERT INTO clinics 
            (clinic_name, first_name, last_name, contact, address, lat, lng, admin_email, password, verification_file, face_auth_file, id_validation_file, created_at)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())");

        // ✅ FIXED BIND TYPES (address is STRING)
        $stmt->bind_param(
            "sssssddssssss",
            $clinic_name,
            $first_name,
            $last_name,
            $contact,
            $address,
            $lat,
            $lng,
            $admin_email,
            $hashed_password,
            $verification_path,
            $face_path,
            $id_path
        );

        if ($stmt->execute()) {
            $success = true;
            $_SESSION['success_msg'] = "Clinic registered successfully!";
        } else {
            $errors[] = "Database error: " . $stmt->error;
        }

        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Clinic Manager Registration | Vet Clinics</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<link rel="shortcut icon" type="image/x-icon" href="assets/images/favicon.ico">
<link rel="stylesheet" href="assets/css/bootstrap.min.css">
<link rel="stylesheet" href="assets/vendors/css/vendors.min.css">
<link rel="stylesheet" href="assets/css/theme.min.css">

<!-- Leaflet CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">

<style>
    body { background: #f6f7fb; }
    .auth-wrapper { min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 40px 15px; }
    .auth-card { max-width: 560px; width: 100%; border-radius: 10px; }
    .auth-logo img { max-height: 60px; }
    .form-control { padding: 6px 10px; }
    .btn-register { background-color: #0d6efd; border: none; font-weight: 600; }
    .btn-register:hover { background-color: #0b5ed7; }
    .small-text { font-size: 13px; }
    #map-container { height: 300px; min-height: 300px; margin-bottom: 15px; border-radius: 5px; }
</style>
</head>

<body>
<div class="auth-wrapper">
<div class="card auth-card shadow-sm">
<div class="card-body p-4">

<h4 class="text-center mb-1">Clinic Manager Registration</h4>
<p class="text-center text-muted mb-4">Admin Account Setup</p>

<?php if(!empty($errors)): ?>
<div class="alert alert-danger">
<?php foreach($errors as $error) echo "<p>$error</p>"; ?>
</div>
<?php endif; ?>

<?php if($success): ?>
<div class="alert alert-success">
Clinic registered successfully!
</div>
<?php endif; ?>

<form method="POST" enctype="multipart/form-data">

<div class="mb-3">
<label class="form-label fw-semibold">Clinic Name</label>
<input type="text" name="clinic_name" class="form-control" required>
</div>

<div class="row">
<div class="col-md-6 mb-3">
<label class="form-label fw-semibold">First Name</label>
<input type="text" name="first_name" class="form-control" required>
</div>
<div class="col-md-6 mb-3">
<label class="form-label fw-semibold">Last Name</label>
<input type="text" name="last_name" class="form-control" required>
</div>
</div>

<div class="mb-3">
<label class="form-label fw-semibold">Contact Number</label>
<input type="text" name="contact" class="form-control" required>
</div>

<div class="mb-3">
<label class="form-label fw-semibold">Address</label>
<input type="text" id="address" name="address" class="form-control" required>
</div>

<div class="mb-3">
<label class="form-label fw-semibold">Select Clinic Location on Map</label>
<div id="map-container"></div>
<button type="button" id="locate-btn" class="btn btn-outline-primary w-100 mt-2">Locate Me</button>
</div>

<input type="hidden" id="latitude" name="latitude">
<input type="hidden" id="longitude" name="longitude">

<div class="mb-3">
<label class="form-label fw-semibold">Admin Email</label>
<input type="email" name="admin_email" class="form-control" required>
</div>

<div class="row">
<div class="col-md-6 mb-3">
<label class="form-label fw-semibold">Password</label>
<input type="password" name="password" class="form-control" required>
</div>
<div class="col-md-6 mb-3">
<label class="form-label fw-semibold">Confirm Password</label>
<input type="password" name="confirm_password" class="form-control" required>
</div>
</div>

<div class="mb-3">
<label class="form-label fw-semibold">Clinic Verification Document</label>
<input type="file" name="verification_doc" class="form-control" required>
</div>

<div class="mb-3">
<label class="form-label fw-semibold">Government ID for Verification</label>
<input type="file" name="id_verification" class="form-control" required>
</div>

<div class="mb-3">
<label class="form-label fw-semibold">Face Recognition Photo</label>
<input type="file" name="face_recognition" class="form-control" required>
</div>

<div class="form-check mb-4">
<input class="form-check-input" type="checkbox" required>
<label class="form-check-label small-text">
I confirm the information provided is accurate and agree to the Terms & Conditions
</label>
</div>

<button type="submit" class="btn btn-register w-100 text-white">
Register Clinic
</button>

</form>

</div>
</div>
</div>

<script src="assets/vendors/js/vendors.min.js"></script>
<script src="assets/js/common-init.min.js"></script>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
document.addEventListener("DOMContentLoaded", function() {
    delete L.Icon.Default.prototype._getIconUrl;
    L.Icon.Default.mergeOptions({
        iconRetinaUrl: 'https://unpkg.com/leaflet@1.9.4/dist/images/marker-icon-2x.png',
        iconUrl: 'https://unpkg.com/leaflet@1.9.4/dist/images/marker-icon.png',
        shadowUrl: 'https://unpkg.com/leaflet@1.9.4/dist/images/marker-shadow.png'
    });

    var map = L.map('map-container').setView([14.5995, 120.9842], 13);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(map);

    var marker = L.marker([14.5995, 120.9842], { draggable: true }).addTo(map);

    var latInput = document.getElementById('latitude');
    var lngInput = document.getElementById('longitude');

    function updateLatLng(lat, lng) {
        latInput.value = lat;
        lngInput.value = lng;
    }

    updateLatLng(marker.getLatLng().lat, marker.getLatLng().lng);

    marker.on('dragend', function(e) {
        updateLatLng(e.target.getLatLng().lat, e.target.getLatLng().lng);
    });

    map.on('click', function(e) {
        marker.setLatLng(e.latlng);
        updateLatLng(e.latlng.lat, e.latlng.lng);
    });

    document.getElementById('locate-btn').addEventListener('click', function() {
        navigator.geolocation.getCurrentPosition(function(position) {
            var lat = position.coords.latitude;
            var lng = position.coords.longitude;
            marker.setLatLng([lat, lng]);
            map.setView([lat, lng], 15);
            updateLatLng(lat, lng);
        });
    });
});
</script>

</body>
</html>
