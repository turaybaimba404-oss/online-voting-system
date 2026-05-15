<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Online Voting System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #0a0a2e;
            color: white;
        }
        .hero {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
        }
        .card-vote {
            background: rgba(255,255,255,0.1);
            border-radius: 20px;
            padding: 40px;
            backdrop-filter: blur(10px);
        }
        .btn-vote {
            background-color: #28a745;
            color: white;
            padding: 15px 40px;
            border-radius: 50px;
            font-size: 18px;
            border: none;
            width: 100%;
            margin-top: 10px;
        }
        .btn-vote:hover {
            background-color: #218838;
            color: white;
        }
        .btn-admin {
            background-color: #007bff;
            color: white;
            padding: 15px 40px;
            border-radius: 50px;
            font-size: 18px;
            border: none;
            width: 100%;
            margin-top: 10px;
        }
    </style>
</head>
<body>
<div class="hero">
    <div class="card-vote col-md-6 col-11">
        <img src="https://cdn-icons-png.flaticon.com/512/3097/3097144.png" 
             width="100" alt="Vote Icon"><br><br>
        <h1 class="fw-bold">Online Voting System</h1>
        <p class="lead mt-3">
            A secure, transparent and professional 
            electoral voting platform
        </p>
        <hr style="border-color: rgba(255,255,255,0.3)">
        <p>Cast your vote securely from your 
           phone or computer</p>
        <a href="register.php" class="btn btn-vote">
            Register to Vote
        </a>
        <a href="login.php" class="btn btn-vote mt-2">
            Login and Vote
        </a>
        <a href="results.php" class="btn btn-admin mt-2">
            View Live Results
        </a>
        <a href="admin/login.php" 
           class="btn btn-admin mt-2">
            Admin Panel
        </a>
        <br><br>
        <small style="opacity:0.6">
            Secured with OTP verification 
            and face recognition
        </small>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>