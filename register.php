<?php
session_start();
require_once "config/db.php";

// MANUAL PHPMailer INCLUDES
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';
require 'PHPMailer/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Handle AJAX form submission
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['ajax'])) {

    $errors = [];

    // Sanitize inputs
    $firstName   = trim($_POST["firstName"] ?? "");
    $middleName  = trim($_POST["middleName"] ?? "");
    $lastName    = trim($_POST["lastName"] ?? "");
    $email       = trim($_POST["emailReg"] ?? "");   // FIXED
    $contact     = trim($_POST["contact"] ?? "");
    $address     = trim($_POST["address"] ?? "");
    $barangay    = trim($_POST["barangay"] ?? "");   // FIXED
    $password    = $_POST["passwordReg"] ?? "";
    $confirmPass = $_POST["confirmPassword"] ?? "";

    // Defaults
    $role = "user";
    $verificationCode = bin2hex(random_bytes(16));
    $isVerified = 0;

    // ---------- VALIDATION ----------
    if ($firstName === "" || $lastName === "") {
        $errors[] = "First and last name are required.";
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email address.";
    }

    if (!preg_match("/^09\d{9}$/", $contact)) {
        $errors[] = "Invalid Philippine mobile number.";
    }

    if ($barangay === "") {
        $errors[] = "Barangay is required.";
    }

    if (strlen($password) < 8) {
        $errors[] = "Password must be at least 8 characters.";
    }

    if ($password !== $confirmPass) {
        $errors[] = "Passwords do not match.";
    }

    // ---------- CHECK DUPLICATE EMAIL ----------
    if (empty($errors)) {
        $check = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $check->bind_param("s", $email);
        $check->execute();
        $check->store_result();

        if ($check->num_rows > 0) {
            $errors[] = "Email is already registered.";
        }
        $check->close();
    }

    // ---------- INSERT USER ----------
    if (empty($errors)) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $conn->prepare("
            INSERT INTO users 
            (first_name, middle_name, last_name, contact, address, barangay, email, password, role, verification_code, is_verified, created_at)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())
        ");

        // FIXED ORDER + TYPE COUNT
        $stmt->bind_param(
            "ssssssssssi",
            $firstName,
            $middleName,
            $lastName,
            $contact,
            $address,
            $barangay,        // moved here
            $email,
            $hashedPassword,
            $role,
            $verificationCode,
            $isVerified
        );

        if ($stmt->execute()) {

            $mail = new PHPMailer(true);
            try {
                $mail->isSMTP();
                $mail->Host       = 'smtp.gmail.com';
                $mail->SMTPAuth   = true;
                $mail->Username   = 'johnchristianloyola203@gmail.com';
                $mail->Password   = 'oets wjer nbbt xlii'; // MOVE TO ENV LATER
                $mail->SMTPSecure = 'tls';
                $mail->Port       = 587;

                $mail->setFrom('johnchristianloyola203@gmail.com', 'VetClinic');
                $mail->addAddress($email, $firstName . ' ' . $lastName);

                $mail->isHTML(true);
                $mail->Subject = 'VetClinic Registration Confirmation';
                $verificationLink = "http://localhost/MediNest/verify.php?code=$verificationCode";
                $mail->Body = "
                    <h3>Thank you for registering, $firstName!</h3>
                    <p>Please click the link below to verify your email and activate your account:</p>
                    <a href='$verificationLink'>Verify Email</a>
                ";

                $mail->send();

                echo json_encode([
                    "success" => true,
                    "message" => "A verification email has been sent to $email."
                ]);

            } catch (Exception $e) {
                echo json_encode([
                    "success" => false,
                    "errors" => ["Message could not be sent. Mailer Error: {$mail->ErrorInfo}"]
                ]);
            }

        } else {
            echo json_encode([
                "success" => false,
                "errors" => ["Registration failed. Please try again."]
            ]);
        }

        $stmt->close();

    } else {
        echo json_encode([
            "success" => false,
            "errors" => $errors
        ]);
    }

    exit;
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Register | VetClinic</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <style>
    body {
      background: linear-gradient(135deg, #e5efeb, #e0f2f2);
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
    }
    .register-card {
      max-width: 520px;
      width: 100%;
      border-radius: 16px;
      box-shadow: 0 20px 40px rgba(0,0,0,.15);
      padding: 30px;
    }
    .form-control { border-radius: 10px; }
    .btn-primary { border-radius: 10px; padding: 10px; }
  </style>
</head>
<body>

<div class="card register-card">
  <h3 class="text-center mb-3">Create Account</h3>
  <form id="registerForm" novalidate>
    <div class="row g-3">
      <div class="col-md-4">
        <label class="form-label">First Name</label>
        <input type="text" name="firstName" class="form-control" required>
      </div>
      <div class="col-md-4">
        <label class="form-label">Middle Name</label>
        <input type="text" name="middleName" class="form-control">
      </div>
      <div class="col-md-4">
        <label class="form-label">Last Name</label>
        <input type="text" name="lastName" class="form-control" required>
      </div>
    </div>

    <div class="mt-3">
      <label class="form-label">Email Address</label>
      <input type="email" name="emailReg" class="form-control" required>
    </div>

    <div class="mt-3">
      <label class="form-label">Contact Number</label>
      <input type="tel" name="contact" class="form-control" placeholder="09xxxxxxxxx" required pattern="09\d{9}" maxlength="11">
    </div>

    <div class="mt-3">
      <label class="form-label">Address</label>
      <textarea name="address" class="form-control" rows="2"></textarea>
    </div>

<div class="mb-3">
  <label for="barangay" class="form-label">Barangay (Dasmariñas)</label>
  <select class="form-select" id="barangay"  name="barangay" required>
    <option value="" selected disabled>Select Barangay</option>

    <option value="burol">Burol</option>
    <option value="burol-1">Burol I</option>
    <option value="burol-2">Burol II</option>
    <option value="burol-3">Burol III</option>
    <option value="datu-esmael">Datu Esmael</option>
    <option value="emmanuel-bergado-1">Emmanuel Bergado I</option>
    <option value="emmanuel-bergado-2">Emmanuel Bergado II</option>
    <option value="fatima-1">Fatima I</option>
    <option value="fatima-2">Fatima II</option>
    <option value="fatima-3">Fatima III</option>
    <option value="h-2">H-2</option>
    <option value="langkaan-1">Langkaan I</option>
    <option value="langkaan-2">Langkaan II</option>
    <option value="luzviminda-1">Luzviminda I</option>
    <option value="luzviminda-2">Luzviminda II</option>
    <option value="luzviminda-3">Luzviminda III</option>
    <option value="paliparan-1">Paliparan I</option>
    <option value="paliparan-2">Paliparan II</option>
    <option value="paliparan-3">Paliparan III</option>
    <option value="sabang">Sabang</option>
    <option value="salawag">Salawag</option>
    <option value="salitran-1">Salitran I</option>
    <option value="salitran-2">Salitran II</option>
    <option value="salitran-3">Salitran III</option>
    <option value="salitran-4">Salitran IV</option>
    <option value="sampaloc-1">Sampaloc I</option>
    <option value="sampaloc-2">Sampaloc II</option>
    <option value="sampaloc-3">Sampaloc III</option>
    <option value="sampaloc-4">Sampaloc IV</option>
    <option value="sampaloc-5">Sampaloc V</option>
    <option value="san-agustin-1">San Agustin I</option>
    <option value="san-agustin-2">San Agustin II</option>
    <option value="san-agustin-3">San Agustin III</option>
    <option value="san-andres-1">San Andres I</option>
    <option value="san-andres-2">San Andres II</option>
    <option value="san-antonio-de-padua-1">San Antonio De Padua I</option>
    <option value="san-antonio-de-padua-2">San Antonio De Padua II</option>
    <option value="san-dionisio">San Dionisio</option>
    <option value="san-esteban">San Esteban</option>
    <option value="san-francisco-1">San Francisco I</option>
    <option value="san-francisco-2">San Francisco II</option>
    <option value="san-isidro-labrador-1">San Isidro Labrador I</option>
    <option value="san-isidro-labrador-2">San Isidro Labrador II</option>
    <option value="san-jose">San Jose</option>
    <option value="san-juan-1">San Juan I</option>
    <option value="san-juan-2">San Juan II</option>
    <option value="san-lorenzo-ruiz-1">San Lorenzo Ruiz I</option>
    <option value="san-lorenzo-ruiz-2">San Lorenzo Ruiz II</option>
    <option value="san-luis-1">San Luis I</option>
    <option value="san-luis-2">San Luis II</option>
    <option value="san-manuel-1">San Manuel I</option>
    <option value="san-manuel-2">San Manuel II</option>
    <option value="san-mateo">San Mateo</option>
    <option value="san-miguel">San Miguel</option>
    <option value="san-nicolas-1">San Nicolas I</option>
    <option value="san-nicolas-2">San Nicolas II</option>
    <option value="san-roque">San Roque</option>
    <option value="san-simon">San Simon</option>
    <option value="santa-cristina-1">Santa Cristina I</option>
    <option value="santa-cristina-2">Santa Cristina II</option>
    <option value="santa-fe">Santa Fe</option>
    <option value="santa-lucia">Santa Lucia</option>
    <option value="santa-maria">Santa Maria</option>
    <option value="santo-cristo">Santo Cristo</option>
    <option value="santo-nino-1">Santo Niño I</option>
    <option value="santo-nino-2">Santo Niño II</option>
    <option value="victoria-reyes">Victoria Reyes</option>
    <option value="zone-1">Zone I</option>
    <option value="zone-1a">Zone I-A</option>
    <option value="zone-2">Zone II</option>
    <option value="zone-3">Zone III</option>
    <option value="zone-4">Zone IV</option>

  </select>
</div>


    <div class="mt-3">
      <label class="form-label">Password</label>
      <input type="password" name="passwordReg" class="form-control" required minlength="8">
    </div>

    <div class="mt-3">
      <label class="form-label">Confirm Password</label>
      <input type="password" name="confirmPassword" class="form-control" required minlength="8">
    </div>

    <div class="form-check mt-3">
      <input class="form-check-input" type="checkbox" required>
      <label class="form-check-label">I agree to the Terms & Conditions</label>
    </div>

    <div class="d-grid mt-4">
      <button type="submit" class="btn btn-primary">Register</button>
    </div>

    <div class="text-center mt-3">
      <small>Already have an account? <a href="index.php">Login</a></small>
    </div>
  </form>
</div>

<script>
document.getElementById('registerForm').addEventListener('submit', function(e){
    e.preventDefault();
    let formData = new FormData(this);
    formData.append('ajax', 1); // mark as AJAX request

    fetch('register.php', { method: 'POST', body: formData })
    .then(res => res.json())
    .then(data => {
        if(data.success){
            Swal.fire({
                title: 'Almost there!',
                html: data.message + '<br><br>Please check your email and click the verification link to continue.',
                icon: 'info',
                allowOutsideClick: false,
                allowEscapeKey: false,
                showConfirmButton: false,
                didOpen: () => { Swal.showLoading(); }
            });
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Oops!',
                html: data.errors.join('<br>')
            });
        }
    })
    .catch(err => {
        Swal.fire({
            icon: 'error',
            title: 'Error!',
            text: 'Something went wrong. Please try again.'
        });
    });
});
</script>

</body>
</html>
