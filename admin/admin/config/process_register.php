<?php
session_start();
require_once 'config/database.php';

if($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: register.php');
    exit();
}

$full_name = trim($_POST['full_name'] ?? '');
$nin = trim($_POST['nin'] ?? '');
$phone = trim($_POST['phone'] ?? '');
$email = trim($_POST['email'] ?? '');
$dob = trim($_POST['dob'] ?? '');
$password = trim($_POST['password'] ?? '');
$confirm = trim($_POST['confirm_password'] ?? '');

if(!$full_name || !$nin || !$phone || 
   !$password || !$dob) {
    $_SESSION['error'] = 
        'Please fill all required fields';
    header('Location: register.php');
    exit();
}

if($password !== $confirm) {
    $_SESSION['error'] = 
        'Passwords do not match';
    header('Location: register.php');
    exit();
}

if(strlen($password) < 6) {
    $_SESSION['error'] = 
        'Password must be at least 6 characters';
    header('Location: register.php');
    exit();
}

$conn = connectDB();
if(!$conn) {
    $_SESSION['error'] = 
        'Database connection failed';
    header('Location: register.php');
    exit();
}

$nin = $conn->real_escape_string($nin);
$phone = $conn->real_escape_string($phone);

// Check if NIN already exists
$check = $conn->query(
    "SELECT id FROM voters 
     WHERE nin='$nin' OR phone='$phone'"
);

if($check->num_rows > 0) {
    $_SESSION['error'] = 
        'NIN or phone number already registered';
    header('Location: register.php');
    exit();
}

// Handle photo upload
$photo_url = '';
if(isset($_FILES['photo']) && 
   $_FILES['photo']['error'] === 0) {
    $upload_dir = 'uploads/voters/';
    if(!is_dir($upload_dir)) {
        mkdir($upload_dir, 0755, true);
    }
    $ext = pathinfo(
        $_FILES['photo']['name'],
        PATHINFO_EXTENSION
    );
    $filename = uniqid('voter_') . '.' . $ext;
    if(move_uploaded_file(
        $_FILES['photo']['tmp_name'],
        $upload_dir . $filename
    )) {
        $photo_url = $upload_dir . $filename;
    }
}

$hashed_password = password_hash(
    $password, 
    PASSWORD_BCRYPT
);

$full_name = $conn->real_escape_string($full_name);
$email = $conn->real_escape_string($email);
$dob = $conn->real_escape_string($dob);
$photo_url = $conn->real_escape_string($photo_url);

$sql = "INSERT INTO voters 
        (full_name, nin, phone, email, dob, 
         photo_url, password, is_verified) 
        VALUES (
            '$full_name',
            '$nin',
            '$phone',
            '$email',
            '$dob',
            '$photo_url',
            '$hashed_password',
            1
        )";

if($conn->query($sql)) {
    $voter_id = $conn->insert_id;
    $ip = $_SERVER['REMOTE_ADDR'];
    $conn->query(
        "INSERT INTO audit_logs 
         (voter_id, action, ip_address) 
         VALUES (
             '$voter_id',
             'New voter registered',
             '$ip'
         )"
    );
    $_SESSION['success'] = 
        'Registration successful! 
         You can now login and vote.';
    header('Location: login.php');
} else {
    $_SESSION['error'] = 
        'Registration failed. Please try again.';
    header('Location: register.php');
}

$conn->close();
?>