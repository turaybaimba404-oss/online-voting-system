<?php
define('DB_HOST', '01s3vb.h.filess.io');
define('DB_PORT', '3307');
define('DB_USER', 'votingsystem_entirefox');
define('DB_PASS', 'f27938827ce05ebb28d2b8660856d00d44afb229');
define('DB_NAME', 'votingsystem_entirefox');

function connectDB() {
    $conn = new mysqli(
        DB_HOST,
        DB_USER,
        DB_PASS,
        DB_NAME,
        DB_PORT
    );
    if($conn->connect_error) {
        die(json_encode([
            'success' => false,
            'message' => 'Database connection failed: ' 
                . $conn->connect_error
        ]));
    }
    $conn->set_charset('utf8mb4');
    return $conn;
}
?>