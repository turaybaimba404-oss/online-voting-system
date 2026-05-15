<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify OTP</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #0a0a2e; color: white; }
        .verify-card {
            background: rgba(255,255,255,0.1);
            border-radius: 20px;
            padding: 40px;
            backdrop-filter: blur(10px);
            margin: 60px auto;
            text-align: center;
        }
        .otp-inputs {
            display: flex;
            justify-content: center;
            gap: 10px;
            margin: 30px 0;
        }
        .otp-box {
            width: 55px;
            height: 55px;
            text-align: center;
            font-size: 24px;
            font-weight: bold;
            background: rgba(255,255,255,0.2);
            border: 2px solid rgba(255,255,255,0.3);
            border-radius: 10px;
            color: white;
        }
        .otp-box:focus {
            border-color: #28a745;
            outline: none;
            background: rgba(255,255,255,0.3);
        }
        .btn-verify {
            background-color: #28a745;
            color: white;
            padding: 12px 40px;
            border-radius: 50px;
            font-size: 16px;
            border: none;
            width: 100%;
            margin-top: 10px;
        }
        .btn-verify:hover { background-color: #218838; }
        .btn-resend {
            background: transparent;
            border: 1px solid rgba(255,255,255,0.4);
            color: rgba(255,255,255,0.7);
            padding: 10px 30px;
            border-radius: 50px;
            font-size: 14px;
            width: 100%;
            margin-top: 10px;
        }
        .btn-resend:hover {
            border-color: white;
            color: white;
        }
        .phone-display {
            background: rgba(40,167,69,0.2);
            border: 1px solid rgba(40,167,69,0.4);
            border-radius: 10px;
            padding: 10px 20px;
            display: inline-block;
            margin: 10px 0;
            color: #28a745;
            font-weight: bold;
        }
        .timer {
            font-size: 14px;
            opacity: 0.6;
            margin-top: 10px;
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
    <div class="verify-card col-md-5 col-12">

        <img src="https://cdn-icons-png.flaticon.com/512/3097/3097144.png"
             width="70" alt="Vote"><br><br>
        <h3 class="fw-bold">OTP Verification</h3>
        <p style="opacity:0.7">
            A 6-digit code has been sent to your phone
        </p>

        <?php if(isset($_SESSION['phone'])): ?>
        <div class="phone-display">
            📱 <?php echo htmlspecialchars(
                substr($_SESSION['phone'], 0, 6) . '****'
            ); ?>
        </div>
        <?php endif; ?>

        <?php if(isset($_SESSION['error'])): ?>
        <div class="alert alert-danger mt-3">
            <?php echo $_SESSION['error'];
            unset($_SESSION['error']); ?>
        </div>
        <?php endif; ?>

        <form action="process_verify.php" method="POST">
            <div class="otp-inputs">
                <input type="text" class="otp-box"
                       maxlength="1" id="otp1"
                       name="otp1" required>
                <input type="text" class="otp-box"
                       maxlength="1" id="otp2"
                       name="otp2" required>
                <input type="text" class="otp-box"
                       maxlength="1" id="otp3"
                       name="otp3" required>
                <input type="text" class="otp-box"
                       maxlength="1" id="otp4"
                       name="otp4" required>
                <input type="text" class="otp-box"
                       maxlength="1" id="otp5"
                       name="otp5" required>
                <input type="text" class="otp-box"
                       maxlength="1" id="otp6"
                       name="otp6" required>
            </div>

            <button type="submit" class="btn-verify">
                Verify OTP
            </button>
        </form>

        <form action="resend_otp.php" method="POST">
            <button type="submit" class="btn-resend">
                Resend OTP Code
            </button>
        </form>

        <div class="timer" id="timer">
            Code expires in: <span id="countdown">05:00</span>
        </div>

        <a href="login.php" class="back-link">
            ← Back to Login
        </a>
    </div>
</div>

<script>
// Auto move to next OTP box
const otpBoxes = document.querySelectorAll('.otp-box');
otpBoxes.forEach((box, index) => {
    box.addEventListener('input', function() {
        if(this.value.length === 1) {
            if(index < otpBoxes.length - 1) {
                otpBoxes[index + 1].focus();
            }
        }
    });
    box.addEventListener('keydown', function(e) {
        if(e.key === 'Backspace' && !this.value) {
            if(index > 0) {
                otpBoxes[index - 1].focus();
            }
        }
    });
});

// Countdown timer 5 minutes
let timeLeft = 300;
const countdown = document.getElementById('countdown');
const timer = setInterval(function() {
    timeLeft--;
    let minutes = Math.floor(timeLeft / 60);
    let seconds = timeLeft % 60;
    countdown.textContent = 
        String(minutes).padStart(2,'0') + ':' + 
        String(seconds).padStart(2,'0');
    if(timeLeft <= 0) {
        clearInterval(timer);
        countdown.textContent = 'EXPIRED';
        countdown.style.color = 'red';
    }
}, 1000);
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>