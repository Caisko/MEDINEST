<?php
require_once "config/db.php";
header('Content-Type: application/json');

$user_id = intval($_GET['user_id'] ?? 0);
$response = ['verified' => false];

if($user_id > 0){
    $stmt = $conn->prepare("SELECT verified_at FROM users WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->bind_result($verified_at);
    if($stmt->fetch() && $verified_at !== null){
        $response['verified'] = true;
    }
    $stmt->close();
}

echo json_encode($response);
