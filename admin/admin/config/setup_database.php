<?php
require_once 'config/database.php';

$conn = connectDB();

$tables = [];

$tables[] = "CREATE TABLE IF NOT EXISTS voters (
    id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(100) NOT NULL,
    nin VARCHAR(50) UNIQUE NOT NULL,
    phone VARCHAR(20) UNIQUE NOT NULL,
    email VARCHAR(100),
    dob DATE,
    photo_url VARCHAR(255),
    device_fingerprint VARCHAR(255),
    password VARCHAR(255) NOT NULL,
    has_voted TINYINT(1) DEFAULT 0,
    is_verified TINYINT(1) DEFAULT 0,
    registered_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

$tables[] = "CREATE TABLE IF NOT EXISTS elections (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(150) NOT NULL,
    description TEXT,
    start_time DATETIME,
    end_time DATETIME,
    status ENUM('upcoming','active','closed') 
        DEFAULT 'upcoming',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

$tables[] = "CREATE TABLE IF NOT EXISTS candidates (
    id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(100) NOT NULL,
    party VARCHAR(100),
    election VARCHAR(150),
    photo_url VARCHAR(255),
    bio TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

$tables[] = "CREATE TABLE IF NOT EXISTS ballots (
    id INT AUTO_INCREMENT PRIMARY KEY,
    election_id INT NOT NULL,
    candidate_id INT NOT NULL,
    cast_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

$tables[] = "CREATE TABLE IF NOT EXISTS audit_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    voter_id INT,
    action VARCHAR(255) NOT NULL,
    ip_address VARCHAR(45),
    device_info TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

$tables[] = "CREATE TABLE IF NOT EXISTS admins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    secret_code VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

$errors = [];
$success = [];

foreach($tables as $sql) {
    if($conn->query($sql)) {
        $success[] = "Table created successfully";
    } else {
        $errors[] = "Error: " . $conn->error;
    }
}

// Insert first election
$conn->query("INSERT IGNORE INTO elections 
    (title, description, status) 
    VALUES (
        'Presidential Election 2025',
        'Official Gambia Presidential Election',
        'active'
    )");

// Insert admin account
$password = password_hash('admin123', PASSWORD_BCRYPT);
$secret = password_hash('secret123', PASSWORD_BCRYPT);
$conn->query("INSERT IGNORE INTO admins 
    (username, password, secret_code) 
    VALUES ('admin', '$password', '$secret')");

$conn->close();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Database Setup</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: #0a0a2e;
            color: white;
            padding: 40px;
        }
        .card {
            background: rgba(255,255,255,0.1);
            border-radius: 15px;
            padding: 30px;
            max-width: 600px;
            margin: 0 auto;
        }
    </style>
</head>
<body>
<div class="card">
    <h3 class="text-center fw-bold mb-4">
        🗄️ Database Setup
    </h3>

    <?php if(empty($errors)): ?>
    <div class="alert alert-success">
        ✅ All tables created successfully!
    </div>
    <div class="alert alert-info">
        <strong>Admin Login Details:</strong><br>
        Username: admin<br>
        Password: admin123<br>
        Secret Code: secret123
    </div>
    <div class="text-center mt-3">
        <a href="index.php"
           style="background:#28a745;
                  color:white;
                  padding:12px 30px;
                  border-radius:50px;
                  text-decoration:none;">
            Go to Voting App →
        </a>
    </div>
    <?php else: ?>
    <div class="alert alert-danger">
        <strong>Errors found:</strong><br>
        <?php foreach($errors as $e): ?>
            <?php echo $e; ?><br>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>
</div>
</body>
</html>