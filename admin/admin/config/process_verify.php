<?php
session_start();
require_once 'config/database.php';

if(!isset($_SESSION['voter_id'])) {
    header('Location: login.php');
    exit();
}

$otp1 = $_POST['otp1'] ?? '';
$otp2 = $_POST['otp2'] ?? '';
$otp3 = $_POST['otp3'] ?? '';
$otp4 = $_POST['otp4'] ?? '';
$otp5 = $_POST['otp5'] ?? '';
$otp6 = $_POST['otp6'] ?? '';

$entered_otp = $otp1.$otp2.$otp3.$otp4.$otp5.$otp6;
$stored_otp = $_SESSION['otp'] ?? '';
$otp_time = $_SESSION['otp_time'] ?? 0;

// Check OTP expiry - 5 minutes
if(time() - $otp_time > 300) {
    $_SESSION['error'] = 
        'OTP has expired. Please login again.';
    header('Location: login.php');
    exit();
}

if($entered_otp != $stored_otp) {
    $_SESSION['error'] = 
        'Invalid OTP code. Please try again.';
    header('Location: verify.php');
    exit();
}

// OTP verified successfully
unset($_SESSION['otp']);
unset($_SESSION['otp_time']);

$conn = connectDB();
$voter_id = $_SESSION['voter_id'];
$ip = $_SERVER['REMOTE_ADDR'];

$conn->query(
    "INSERT INTO audit_logs 
     (voter_id, action, ip_address) 
     VALUES (
         '$voter_id',
         'OTP verified successfully',
         '$ip'
     )"
);

$conn->close();

header('Location: ballot.php');
exit();
?>