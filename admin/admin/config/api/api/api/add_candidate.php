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

if(!$full_name || !$party || !$election) {
    echo json_encode([
        'success' => false,
        'message' => 'Please fill all required fields'
    ]);
    exit();
}

// Handle photo upload
$photo_url = 'https://cdn-icons-png.flaticon.com/512/3135/3135715.png';

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
    $upload_path = $upload_dir . $filename;

    if(move_uploaded_file(
        $_FILES['photo']['tmp_name'],
        $upload_path
    )) {
        $photo_url = '../uploads/candidates/' . $filename;
    }
}

$sql = "INSERT INTO candidates 
        (full_name, party, election, photo_url, bio) 
        VALUES (
            '$full_name',
            '$party', 
            '$election',
            '$photo_url',
            '$bio'
        )";

if($conn->query($sql)) {
    // Log the action
    $admin_id = $_SESSION['admin_id'];
    $ip = $_SERVER['REMOTE_ADDR'];
    $conn->query(
        "INSERT INTO audit_logs 
         (voter_id, action, ip_address) 
         VALUES (
             '$admin_id',
             'Admin added candidate: $full_name',
             '$ip'
         )"
    );

    echo json_encode([
        'success' => true,
        'message' => 'Candidate added successfully'
    ]);
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Failed to add candidate'
    ]);
}

$conn->close();
?>