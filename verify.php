<?php
require_once "config/db.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Verify Account | VetClinic</title>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>

<?php
$code = $_GET['code'] ?? '';
if ($code === '') {
    echo "<script>
        Swal.fire({
            icon: 'error',
            title: 'Invalid Link!',
            text: 'No verification code provided.'
        }).then(() => { window.location.href = 'index.php'; });
    </script>";
    exit;
}

// Check if code exists
$stmt = $conn->prepare("SELECT id, is_verified FROM users WHERE verification_code = ?");
$stmt->bind_param("s", $code);
$stmt->execute();
$stmt->store_result();
$stmt->bind_result($id, $isVerified);
$stmt->fetch();

if ($stmt->num_rows === 0) {
    echo "<script>
        Swal.fire({
            icon: 'error',
            title: 'Invalid Link!',
            text: 'This verification code is invalid.'
        }).then(() => { window.location.href = 'index.php'; });
    </script>";
} elseif ($isVerified) {
    echo "<script>
        Swal.fire({
            icon: 'info',
            title: 'Already Verified',
            text: 'Your account has already been verified.'
        }).then(() => { window.location.href = 'index.php'; });
    </script>";
} else {
    // Mark as verified
    $stmtUpdate = $conn->prepare("UPDATE users SET is_verified = 1 WHERE id = ?");
    $stmtUpdate->bind_param("i", $id);
    $stmtUpdate->execute();

    echo "<script>
        Swal.fire({
            icon: 'success',
            title: 'Account Verified!',
            text: 'Your account has been successfully activated.'
        }).then(() => { window.location.href = 'index.php'; });
    </script>";
}

$stmt->close();
$conn->close();
?>

</body>
</html>
