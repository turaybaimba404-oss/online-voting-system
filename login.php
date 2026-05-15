<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Voter Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #0a0a2e; color: white; }
        .login-card {
            background: rgba(255,255,255,0.1);
            border-radius: 20px;
            padding: 40px;
            backdrop-filter: blur(10px);
            margin: 60px auto;
        }
        .form-control {
            background: rgba(255,255,255,0.2);
            border: 1px solid rgba(255,255,255,0.3);
            color: white;
            border-radius: 10px;
            padding: 12px;
        }
        .form-control:focus {
            background: rgba(255,255,255,0.3);
            color: white;
            border-color: #28a745;
            box-shadow: none;
        }
        .form-control::placeholder { color: rgba(255,255,255,0.6); }
        .btn-login {
            background-color: #28a745;
            color: white;
            padding: 12px;
            border-radius: 50px;
            font-size: 16px;
            border: none;
            width: 100%;
            margin-top: 10px;
        }
        .btn-login:hover { background-color: #218838; color: white; }
        label { color: rgba(255,255,255,0.8); margin-bottom: 5px; }
        .back-link {
            color: rgba(255,255,255,0.6);
            text-decoration: none;
            display: block;
            text-align: center;
            margin-top: 15px;
        }
        .back-link:hover { color: white; }
        .security-badges {
            display: flex;
            justify-content: center;
            gap: 10px;
            margin-top: 20px;
            flex-wrap: wrap;
        }
        .badge-item {
            background: rgba(40,167,69,0.2);
            border: 1px solid rgba(40,167,69,0.4);
            border-radius: 20px;
            padding: 5px 12px;
            font-size: 12px;
            color: #28a745;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="login-card col-md-5 col-12">
        <div class="text-center mb-4">
            <img src="https://cdn-icons-png.flaticon.com/512/3097/3097144.png"
                 width="70" alt="Vote"><br><br>
            <h3 class="fw-bold">Voter Login</h3>
            <p style="opacity:0.7">
                Login to cast your vote securely
            </p>
        </div>

        <?php if(isset($_SESSION['error'])): ?>
        <div class="alert alert-danger">
            <?php echo $_SESSION['error'];
            unset($_SESSION['error']); ?>
        </div>
        <?php endif; ?>

        <?php if(isset($_SESSION['success'])): ?>
        <div class="alert alert-success">
            <?php echo $_SESSION['success'];
            unset($_SESSION['success']); ?>
        </div>
        <?php endif; ?>

        <form action="process_login.php" method="POST">

            <div class="mb-3">
                <label>National ID Number (NIN)</label>
                <input type="text" name="nin"
                       class="form-control"
                       placeholder="Enter your NIN"
                       required>
            </div>

            <div class="mb-3">
                <label>Password</label>
                <input type="password" name="password"
                       class="form-control"
                       placeholder="Enter your password"
                       required>
            </div>

            <div class="mb-3">
                <label>Phone Number</label>
                <input type="tel" name="phone"
                       class="form-control"
                       placeholder="e.g. +2207XXXXXXX"
                       required>
            </div>

            <button type="submit" class="btn-login">
                Login and Verify via SMS
            </button>
        </form>

        <div class="security-badges">
            <span class="badge-item">🔒 SSL Secured</span>
            <span class="badge-item">📱 SMS Verified</span>
            <span class="badge-item">👁️ Face Check</span>
            <span class="badge-item">🛡️ One Vote Only</span>
        </div>

        <div class="text-center mt-3">
            <small style="opacity:0.6">
                Don't have an account?
            </small>
            <a href="register.php" 
               style="color:#28a745; text-decoration:none;">
                Register here
            </a>
        </div>

        <a href="index.php" class="back-link">
            ← Back to Home
        </a>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>