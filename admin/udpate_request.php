<?php
// update_request.php
require 'db.php';

// Get POST data
$id = intval($_POST['id'] ?? 0);
$status = $_POST['status'] ?? '';

if (!$id || !in_array($status, ['approved', 'rejected'])) {
    http_response_code(400);
    echo json_encode(["error" => "Invalid request"]);
    exit;
}

// Update status in database
$stmt = $conn->prepare("UPDATE requests SET status=? WHERE id=?");
$stmt->bind_param("si", $status, $id);

if ($stmt->execute()) {
    echo json_encode(["success" => true]);
} else {
    http_response_code(500);
    echo json_encode(["error" => "Update failed"]);
}

$stmt->close();
$conn->close();
?>