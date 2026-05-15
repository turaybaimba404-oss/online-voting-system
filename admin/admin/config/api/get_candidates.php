<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
require_once '../config/database.php';

$conn = connectDB();

$sql = "SELECT id, full_name, party, 
        election, photo_url, bio 
        FROM candidates 
        ORDER BY full_name ASC";

$result = $conn->query($sql);
$candidates = [];

while($row = $result->fetch_assoc()) {
    $candidates[] = $row;
}

echo json_encode($candidates);
$conn->close();
?>