<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #0a0a2e; color: white; }
        .login-card {
            background: rgba(255,255,255,0.1);
            border-radius: 20px;
            padding: 40px;
            backdrop-filter: blur(10px);
            margin: 80px auto;
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
            border-color: #007bff;
            box-shadow: none;
        }
        .form-control::placeholder {
            color: rgba(255,255,255,0.6);
        }
        .btn-login {
            background-color: #007bff;
            color: white;
            padding: 12px;
            border-radius: 50px;
            font-size: 16px;
            border: none;
            width: 100%;
            margin-top: 10px;
        }
        .btn-login:hover {
            background-color: #0056b3;
            color: white;
        }
        label {
            color: rgba(255,255,255,0.8);
            margin-bottom: 5px;
        }
        .admin-badge {
            background: rgba(0,123,255,0.2);
            border: 1px solid rgba(0,123,255,0.4);
            border-radius: 20px;
            padding: 5px 15px;
            font-size: 13px;
            color: #007bff;
            display: inline-block;
            margin-bottom: 15px;
        }
        .back-link {
            color: rgba(255,255,255,0.6);
            text-decoration: none;
            display: block;
            text-align: center;
            margin-top: 15px;
        }
        .back-link:hover { color: white; }
    </style>
</head>
<body>
<div class="container">
    <div class="login-card col-md-5 col-12">
        <div class="text-center mb-4">
            <img src="https://cdn-icons-png.flaticon.com/512/3097/3097144.png"
                 width="70" alt="Admin"><br><br>
            <span class="admin-badge">
                🔐 ADMIN ACCESS ONLY
            </span>
            <h3 class="fw-bold mt-2">Admin Login</h3>
            <p style="opacity:0.7">
                Authorized election officials only
            </p>
        </div>

        <?php if(isset($_SESSION['error'])): ?>
        <div class="alert alert-danger">
            <?php echo $_SESSION['error'];
            unset($_SESSION['error']); ?>
        </div>
        <?php endif; ?>

        <form action="process_login.php" method="POST">
            <div class="mb-3">
                <label>Admin Username</label>
                <input type="text" name="username"
                       class="form-control"
                       placeholder="Enter admin username"
                       required>
            </div>

            <div class="mb-3">
                <label>Admin Password</label>
                <input type="password" name="password"
                       class="form-control"
                       placeholder="Enter admin password"
                       required>
            </div>

            <div class="mb-3">
                <label>Secret Access Code</label>
                <input type="password" name="secret_code"
                       class="form-control"
                       placeholder="Enter secret access code"
                       required>
            </div>

            <button type="submit" class="btn-login">
                🔐 Login to Admin Panel
            </button>
        </form>

        <a href="../index.php" class="back-link">
            ← Back to Home
        </a>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>