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

$conn = connectDB();

$id = $conn->real_escape_string(
    $_POST['candidate_id'] ?? ''
);
$full_name = $conn->real_escape_string(
    $_POST['full_name'] ?? ''
);
$party = $conn->real_escape_string(
    $_POST['party'] ?? ''
);
$election = $conn->real_escape_string(
    $_POST['election'] ?? ''
);
$bio = $conn->real_escape_string(
    $_POST['bio'] ?? ''
);

if(!$id || !$full_name || !$party) {
    echo json_encode([
        'success' => false,
        'message' => 'Missing required fields'
    ]);
    exit();
}

// Handle new photo if uploaded
$photo_update = '';
if(isset($_FILES['photo']) && 
   $_FILES['photo']['error'] === 0) {
    $upload_dir = '../uploads/candidates/';
    if(!is_dir($upload_dir)) {
        mkdir($upload_dir, 0755, true);
    }
    $ext = pathinfo(
        $_FILES['photo']['name'],
        PATHINFO_EXTENSION
    );
    $filename = uniqid('candidate_') . '.' . $ext;
    if(move_uploaded_file(
        $_FILES['photo']['tmp_name'],
        $upload_dir . $filename
    )) {
        $photo_url = '../uploads/candidates/' . $filename;
        $photo_update = ", photo_url='$photo_url'";
    }
}

$sql = "UPDATE candidates SET 
        full_name='$full_name',
        party='$party',
        election='$election',
        bio='$bio'
        $photo_update
        WHERE id='$id'";

if($conn->query($sql)) {
    $ip = $_SERVER['REMOTE_ADDR'];
    $admin_id = $_SESSION['admin_id'];
    $conn->query(
        "INSERT INTO audit_logs 
         (voter_id, action, ip_address) 
         VALUES (
             '$admin_id',
             'Admin edited candidate: $full_name',
             '$ip'
         )"
    );

    echo json_encode([
        'success' => true,
        'message' => 'Candidate updated successfully'
    ]);
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Failed to update candidate'
    ]);
}

$conn->close();
?>