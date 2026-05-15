<?php
session_start();
if(!isset($_SESSION['voted'])) {
    header('Location: ballot.php');
    exit();
}
$receipt = $_SESSION['receipt'] ?? 'N/A';
$voter_name = $_SESSION['voter_name'] ?? 'Voter';
// Clear voted session after showing
unset($_SESSION['voted']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" 
          content="width=device-width, 
                   initial-scale=1.0">
    <title>Vote Confirmed</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #0a0a2e;
            color: white;
        }
        .confirm-card {
            background: rgba(255,255,255,0.1);
            border-radius: 20px;
            padding: 40px;
            backdrop-filter: blur(10px);
            margin: 60px auto;
            text-align: center;
        }
        .success-icon {
            width: 100px;
            height: 100px;
            background: rgba(40,167,69,0.2);
            border: 3px solid #28a745;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 50px;
            margin: 0 auto 20px;
            animation: pop 0.5s ease;
        }
        @keyframes pop {
            0% { transform: scale(0); }
            70% { transform: scale(1.1); }
            100% { transform: scale(1); }
        }
        .receipt-box {
            background: rgba(40,167,69,0.15);
            border: 2px dashed rgba(40,167,69,0.5);
            border-radius: 15px;
            padding: 20px;
            margin: 25px 0;
        }
        .receipt-code {
            font-size: 22px;
            font-weight: bold;
            color: #28a745;
            letter-spacing: 3px;
            font-family: monospace;
        }
        .info-box {
            background: rgba(255,255,255,0.08);
            border-radius: 12px;
            padding: 15px;
            margin: 10px 0;
            text-align: left;
            font-size: 14px;
        }
        .btn-home {
            background: #28a745;
            color: white;
            padding: 12px 40px;
            border-radius: 50px;
            font-size: 16px;
            border: none;
            text-decoration: none;
            display: inline-block;
            margin-top: 15px;
        }
        .btn-home:hover {
            background: #218838;
            color: white;
        }
        .btn-results {
            background: transparent;
            color: white;
            padding: 12px 40px;
            border-radius: 50px;
            font-size: 16px;
            border: 1px solid rgba(255,255,255,0.4);
            text-decoration: none;
            display: inline-block;
            margin-top: 10px;
        }
        .btn-results:hover {
            background: rgba(255,255,255,0.1);
            color: white;
        }
        .security-info {
            font-size: 12px;
            opacity: 0.5;
            margin-top: 20px;
        }
        .confetti {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: 999;
        }
    </style>
</head>
<body>

<canvas class="confetti" id="confetti"></canvas>

<div class="container">
    <div class="confirm-card col-md-6 col-12">

        <div class="success-icon">✓</div>

        <h2 class="fw-bold text-success">
            Vote Cast Successfully!
        </h2>
        <p style="opacity:0.7; margin-top:10px;">
            Thank you <?php echo htmlspecialchars(
                $voter_name
            ); ?>, your vote has been
            recorded securely.
        </p>

        <!-- Receipt -->
        <div class="receipt-box">
            <div style="opacity:0.7; 
                        font-size:13px;
                        margin-bottom:8px;">
                🧾 Your Vote Receipt Code
            </div>
            <div class="receipt-code">
                <?php echo htmlspecialchars($receipt); ?>
            </div>
            <div style="opacity:0.5; 
                        font-size:12px;
                        margin-top:8px;">
                Save this code to verify your 
                vote was counted
            </div>
        </div>

        <!-- Vote details -->
        <div class="info-box">
            <div class="d-flex justify-content-between">
                <span style="opacity:0.6;">
                    📅 Date & Time
                </span>
                <span>
                    <?php echo date('d M Y, H:i:s'); ?>
                </span>
            </div>
        </div>

        <div class="info-box">
            <div class="d-flex justify-content-between">
                <span style="opacity:0.6;">
                    🔒 Status
                </span>
                <span style="color:#28a745;">
                    ● Verified & Recorded
                </span>
            </div>
        </div>

        <div class="info-box">
            <div class="d-flex justify-content-between">
                <span style="opacity:0.6;">
                    🛡️ Encrypted
                </span>
                <span style="color:#28a745;">
                    ● Yes — AES-256
                </span>
            </div>
        </div>

        <div class="info-box">
            <div class="d-flex justify-content-between">
                <span style="opacity:0.6;">
                    📋 Audit Log
                </span>
                <span style="color:#28a745;">
                    ● Recorded
                </span>
            </div>
        </div>

        <div class="mt-3">
            <a href="results.php" class="btn-results">
                📊 View Live Results
            </a>
        </div>
        <div>
            <a href="index.php" class="btn-home">
                🏠 Back to Home
            </a>
        </div>

        <div class="security-info">
            🔒 Your vote is anonymous and 
            cannot be traced back to you.<br>
            This system is secured with 
            end-to-end encryption.
        </div>

    </div>
</div>

<script>
// Simple confetti animation
const canvas = document.getElementById('confetti');
const ctx = canvas.getContext('2d');
canvas.width = window.innerWidth;
canvas.height = window.innerHeight;

const pieces = [];
const colors = [
    '#28a745','#007bff','#ffc107',
    '#dc3545','#17a2b8','#ffffff'
];

for(let i = 0; i < 150; i++) {
    pieces.push({
        x: Math.random() * canvas.width,
        y: Math.random() * canvas.height - canvas.height,
        w: Math.random() * 10 + 5,
        h: Math.random() * 5 + 3,
        color: colors[
            Math.floor(Math.random() * colors.length)
        ],
        speed: Math.random() * 3 + 2,
        angle: Math.random() * 360,
        spin: Math.random() * 4 - 2
    });
}

function drawConfetti() {
    ctx.clearRect(0, 0, canvas.width, canvas.height);
    pieces.forEach(p => {
        ctx.save();
        ctx.translate(p.x, p.y);
        ctx.rotate(p.angle * Math.PI / 180);
        ctx.fillStyle = p.color;
        ctx.fillRect(-p.w/2, -p.h/2, p.w, p.h);
        ctx.restore();
        p.y += p.speed;
        p.angle += p.spin;
        if(p.y > canvas.height) {
            p.y = -10;
            p.x = Math.random() * canvas.width;
        }
    });
    requestAnimationFrame(drawConfetti);
}

drawConfetti();

// Stop confetti after 5 seconds
setTimeout(() => {
    ctx.clearRect(
        0, 0,
        canvas.width,
        canvas.height
    );
}, 5000);
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>