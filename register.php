<?php
session_start();
require_once "config/db.php"; // make sure path is correct

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
    $email       = trim(string: $_POST["emailReg"] ?? "");
    $contact     = trim($_POST["contact"] ?? "");
    $address     = trim($_POST["address"] ?? "");
    $barangay    = trim(string: $_POST["barangay"] ?? "");
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

    if (strlen($password) < 8) {
        $errors[] = "Password must be at least 8 characters.";
    }

    if ($password !== $confirmPass) {
        $errors[] = "Passwords do not match.";
    }

    
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

        $stmt->bind_param(
            "sssssssssi",
            $firstName,
            $middleName,
            $lastName,
            $contact,
            $address,
            $email,
            $barangay,
            $hashedPassword,
            $role,
            $verificationCode,
            $isVerified
        );

        if ($stmt->execute()) {
            // ---------- SEND VERIFICATION EMAIL ----------
            $mail = new PHPMailer(true);
            try {
                $mail->isSMTP();
                $mail->Host       = 'smtp.gmail.com';
                $mail->SMTPAuth   = true;
                $mail->Username   = 'johnchristianloyola203@gmail.com';
                $mail->Password   = 'vmcijyvnhtqshzmg'; // your App Password
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
  <select class="form-select" id="barangay" required>
    <option value="" selected disabled>Select Barangay</option>
    <option>Burol</option>
    <option>Burol I</option>
    <option>Burol II</option>
    <option>Burol III</option>
    <option>Datu Esmael</option>
    <option>Emmanuel Bergado I</option>
    <option>Emmanuel Bergado II</option>
    <option>Fatima I</option>
    <option>Fatima II</option>
    <option>Fatima III</option>
    <option>H-2</option>
    <option>Langkaan I</option>
    <option>Langkaan II</option>
    <option>Luzviminda I</option>
    <option>Luzviminda II</option>
    <option>Luzviminda III</option>
    <option>Paliparan I</option>
    <option>Paliparan II</option>
    <option>Paliparan III</option>
    <option>Sabang</option>
    <option>Salawag</option>
    <option>Salitran I</option>
    <option>Salitran II</option>
    <option>Salitran III</option>
    <option>Salitran IV</option>
    <option>Sampaloc I</option>
    <option>Sampaloc II</option>
    <option>Sampaloc III</option>
    <option>Sampaloc IV</option>
    <option>Sampaloc V</option>
    <option>San Agustin I</option>
    <option>San Agustin II</option>
    <option>San Agustin III</option>
    <option>San Andres I</option>
    <option>San Andres II</option>
    <option>San Antonio De Padua I</option>
    <option>San Antonio De Padua II</option>
    <option>San Dionisio</option>
    <option>San Esteban</option>
    <option>San Francisco I</option>
    <option>San Francisco II</option>
    <option>San Isidro Labrador I</option>
    <option>San Isidro Labrador II</option>
    <option>San Jose</option>
    <option>San Juan I</option>
    <option>San Juan II</option>
    <option>San Lorenzo Ruiz I</option>
    <option>San Lorenzo Ruiz II</option>
    <option>San Luis I</option>
    <option>San Luis II</option>
    <option>San Manuel I</option>
    <option>San Manuel II</option>
    <option>San Mateo</option>
    <option>San Miguel</option>
    <option>San Nicolas I</option>
    <option>San Nicolas II</option>
    <option>San Roque</option>
    <option>San Simon</option>
    <option>Santa Cristina I</option>
    <option>Santa Cristina II</option>
    <option>Santa Fe</option>
    <option>Santa Lucia</option>
    <option>Santa Maria</option>
    <option>Santo Cristo</option>
    <option>Santo Niño I</option>
    <option>Santo Niño II</option>
    <option>Victoria Reyes</option>
    <option>Zone I</option>
    <option>Zone I-A</option>
    <option>Zone II</option>
    <option>Zone III</option>
    <option>Zone IV</option>
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
