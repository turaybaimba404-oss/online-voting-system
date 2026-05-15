<?php
define('DB_HOST', getenv('DB_HOST'));
define('DB_USER', getenv('DB_USER'));
define('DB_PASS', getenv('DB_PASS'));
define('DB_NAME', getenv('DB_NAME'));

function connectDB() {
    $conn = new mysqli(
        DB_HOST,
        DB_USER,
        DB_PASS,
        DB_NAME
    );
    if($conn->connect_error) {
        die(json_encode([
            'success' => false,
            'message' => 'Database connection failed'
        ]));
    }
    $conn->set_charset('utf8mb4');
    return $conn;
}
?>