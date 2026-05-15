<?php
session_start();
require_once '../config/database.php';

if(!isset($_SESSION['voter_id'])) {
    header('Location: ../login.php');
    exit();
}

if($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../ballot.php');
    exit();
}

$conn = connectDB();

$voter_id = $_SESSION['voter_id'];
$candidate_id = $conn->real_escape_string(
    $_POST['candidate_id'] ?? ''
);

if(!$candidate_id) {
    $_SESSION['error'] = 'Please select a candidate';
    header('Location: ../ballot.php');
    exit();
}

// Check if voter already voted
$check = $conn->query(
    "SELECT has_voted FROM voters 
     WHERE id='$voter_id'"
);
$voter = $check->fetch_assoc();

if($voter['has_voted'] == 1) {
    $_SESSION['error'] = 
        'You have already cast your vote';
    header('Location: ../ballot.php');
    exit();
}

// Check election is active
$election_check = $conn->query(
    "SELECT status FROM elections 
     WHERE status='active' LIMIT 1"
);
if($election_check->num_rows === 0) {
    $_SESSION['error'] = 
        'Voting is currently closed';
    header('Location: ../ballot.php');
    exit();
}

// Get election id
$election_row = $election_check->fetch_assoc();

// Begin transaction - cast vote atomically
$conn->begin_transaction();

try {
    // Insert ballot - NO voter_id for secrecy
    $election_result = $conn->query(
        "SELECT id FROM elections 
         WHERE status='active' LIMIT 1"
    );
    $election = $election_result->fetch_assoc();
    $election_id = $election['id'];

    $conn->query(
        "INSERT INTO ballots 
         (election_id, candidate_id) 
         VALUES ('$election_id', '$candidate_id')"
    );

    // Mark voter as voted
    $conn->query(
        "UPDATE voters SET has_voted=1 
         WHERE id='$voter_id'"
    );

    // Generate receipt code
    $receipt = strtoupper(uniqid('VOTE-'));
    $_SESSION['receipt'] = $receipt;
    $_SESSION['voted'] = true;

    // Log the action
    $ip = $_SERVER['REMOTE_ADDR'];
    $conn->query(
        "INSERT INTO audit_logs 
         (voter_id, action, ip_address) 
         VALUES (
             '$voter_id',
             'Vote cast successfully. Receipt: $receipt',
             '$ip'
         )"
    );

    $conn->commit();
    header('Location: ../confirmation.php');
    exit();

} catch(Exception $e) {
    $conn->rollback();
    $_SESSION['error'] = 
        'Voting failed. Please try again.';
    header('Location: ../ballot.php');
    exit();
}

$conn->close();
?>