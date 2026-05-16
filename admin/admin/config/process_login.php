<?php
session_start();
require_once 'config/database.php';

if($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: login.php');
    exit();
}

$nin = trim($_POST['nin'] ?? '');
$password = trim($_POST['password'] ?? '');
$phone = trim($_POST['phone'] ?? '');

if(!$nin || !$password || !$phone) {
    $_SESSION['error'] = 
        'Please fill in all fields';
    header('Location: login.php');
    exit();
}

$conn = connectDB();
if(!$conn) {
    $_SESSION['error'] = 
        'Database connection failed';
    header('Location: login.php');
    exit();
}

$nin = $conn->real_escape_string($nin);
$phone = $conn->real_escape_string($phone);

$result = $conn->query(
    "SELECT * FROM voters 
     WHERE nin='$nin' 
     AND phone='$phone' 
     LIMIT 1"
);

if($result->num_rows === 0) {
    $_SESSION['error'] = 
        'Invalid NIN or phone number';
    header('Location: login.php');
    exit();
}

$voter = $result->fetch_assoc();

if(!password_verify($password, $voter['password'])) {
    $_SESSION['error'] = 
        'Invalid password';
    header('Location: login.php');
    exit();
}

if($voter['is_verified'] == 0) {
    $_SESSION['error'] = 
        'Your account is not verified yet';
    header('Location: login.php');
    exit();
}

// Generate OTP
$otp = rand(100000, 999999);
$_SESSION['otp'] = $otp;
$_SESSION['otp_time'] = time();
$_SESSION['voter_id'] = $voter['id'];
$_SESSION['voter_name'] = $voter['full_name'];
$_SESSION['phone'] = $voter['phone'];

// Log the action
$ip = $_SERVER['REMOTE_ADDR'];
$conn->query(
    "INSERT INTO audit_logs 
     (voter_id, action, ip_address) 
     VALUES (
         '{$voter['id']}',
         'Voter login attempt',
         '$ip'
     )"
);

$conn->close();

// For now redirect to verify page
// In production connect Africa's Talking SMS API
$_SESSION['success'] = 
    'OTP Code for testing: ' . $otp;
header('Location: verify.php');
exit();
?>