<?php
$host = '0ls3vb.h.filess.io';
$port = 3307;
$dbname = 'votingsystem_entirefox';
$username = 'votingsystem_entirefox';
$password = 'f27938827ce05ebb28d2b8660856d00d44afb229';

function connectDB() {
    global $host, $port, $dbname, 
           $username, $password;
    
    $conn = new mysqli(
        $host,
        $username,
        $password,
        $dbname,
        $port
    );
    
    if($conn->connect_error) {
        error_log(
            'Connection failed: ' . 
            $conn->connect_error
        );
        return false;
    }
    
    $conn->set_charset('utf8mb4');
    return $conn;
}
?>