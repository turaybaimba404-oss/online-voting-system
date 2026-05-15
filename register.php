<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Voter Registration</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #0a0a2e; color: white; }
        .form-card {
            background: rgba(255,255,255,0.1);
            border-radius: 20px;
            padding: 30px;
            backdrop-filter: blur(10px);
            margin: 30px auto;
        }
        .form-control {
            background: rgba(255,255,255,0.2);
            border: 1px solid rgba(255,255,255,0.3);
            color: white;
            border-radius: 10px;
        }
        .form-control:focus {
            background: rgba(255,255,255,0.3);
            color: white;
            border-color: #28a745;
            box-shadow: none;
        }
        .form-control::placeholder { color: rgba(255,255,255,0.6); }
        .btn-register {
            background-color: #28a745;
            color: white;
            padding: 12px;
            border-radius: 50px;
            font-size: 16px;
            border: none;
            width: 100%;
            margin-top: 10px;
        }
        .btn-register:hover { background-color: #218838; color: white; }
        label { color: rgba(255,255,255,0.8); margin-bottom: 5px; }
        .page-title { text-align: center; margin-bottom: 20px; }
        .back-link { 
            color: rgba(255,255,255,0.6); 
            text-decoration: none; 
            display: block;
            text-align: center;
            margin-top: 15px;
        }
        .back-link:hover { color: white; }
        .photo-preview {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            object-fit: cover;
            display: none;
            margin: 10px auto;
            border: 3px solid #28a745;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="form-card col-md-6 col-12">
        <div class="page-title">
            <img src="https://cdn-icons-png.flaticon.com/512/3097/3097144.png" 
                 width="60" alt="Vote"><br><br>
            <h3 class="fw-bold">Voter Registration</h3>
            <p style="opacity:0.7">
                Fill in your details to register as a voter
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

        <form action="process_register.php" method="POST" 
              enctype="multipart/form-data">

            <div class="mb-3">
                <label>Full Name</label>
                <input type="text" name="full_name" 
                       class="form-control" 
                       placeholder="Enter your full name" 
                       required>
            </div>

            <div class="mb-3">
                <label>National ID Number (NIN)</label>
                <input type="text" name="nin" 
                       class="form-control" 
                       placeholder="Enter your NIN" 
                       required>
            </div>

            <div class="mb-3">
                <label>Phone Number</label>
                <input type="tel" name="phone" 
                       class="form-control" 
                       placeholder="e.g. +2207XXXXXXX" 
                       required>
            </div>

            <div class="mb-3">
                <label>Email Address (optional)</label>
                <input type="email" name="email" 
                       class="form-control" 
                       placeholder="Enter your email">
            </div>

            <div class="mb-3">
                <label>Date of Birth</label>
                <input type="date" name="dob" 
                       class="form-control" required>
            </div>

            <div class="mb-3">
                <label>Upload Your Photo</label>
                <input type="file" name="photo" 
                       class="form-control" 
                       accept="image/*" 
                       onchange="previewPhoto(this)"
                       required>
                <img id="photoPreview" 
                     class="photo-preview d-block">
            </div>

            <div class="mb-3">
                <label>Create Password</label>
                <input type="password" name="password" 
                       class="form-control" 
                       placeholder="Create a strong password" 
                       required>
            </div>

            <div class="mb-3">
                <label>Confirm Password</label>
                <input type="password" name="confirm_password" 
                       class="form-control" 
                       placeholder="Repeat your password" 
                       required>
            </div>

            <button type="submit" class="btn-register">
                Register Now
            </button>
        </form>

        <a href="index.php" class="back-link">
            ← Back to Home
        </a>
    </div>
</div>

<script>
function previewPhoto(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            var preview = document.getElementById('photoPreview');
            preview.src = e.target.result;
            preview.style.display = 'block';
        }
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>