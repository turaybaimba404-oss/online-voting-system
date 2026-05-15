<?php
header('Content-Type: application/json');
require_once '../config/database.php';

session_start();
if(!isset($_SESSION['admin_id'])) {
    echo json_encode([
        'success' => false,
        'message' => 'Unauthorized'
    ]);
    exit();
}

$data = json_decode(
    file_get_contents('php://input'),
    true
);
$id = $data['id'] ?? '';

if(!$id) {
    echo json_encode([
        'success' => false,
        'message' => 'No candidate ID provided'
    ]);
    exit();
}

$conn = connectDB();
$id = $conn->real_escape_string($id);

// Get candidate name for audit log
$result = $conn->query(
    "SELECT full_name FROM candidates 
     WHERE id='$id'"
);
$candidate = $result->fetch_assoc();
$name = $candidate['full_name'] ?? 'Unknown';

$sql = "DELETE FROM candidates WHERE id='$id'";

if($conn->query($sql)) {
    $ip = $_SERVER['REMOTE_ADDR'];
    $admin_id = $_SESSION['admin_id'];
    $conn->query(
        "INSERT INTO audit_logs 
         (voter_id, action, ip_address) 
         VALUES (
             '$admin_id',
             'Admin deleted candidate: $name',
             '$ip'
         )"
    );

    echo json_encode([
        'success' => true,
        'message' => 'Candidate deleted successfully'
    ]);
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Failed to delete candidate'
    ]);
}

$conn->close();
?>