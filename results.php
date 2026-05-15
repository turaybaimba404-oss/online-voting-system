<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Live Election Results</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body { background-color: #0a0a2e; color: white; }
        .results-header {
            background: rgba(255,255,255,0.1);
            border-radius: 20px;
            padding: 25px;
            text-align: center;
            margin: 20px 0;
            backdrop-filter: blur(10px);
        }
        .result-card {
            background: rgba(255,255,255,0.08);
            border: 1px solid rgba(255,255,255,0.15);
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 15px;
        }
        .candidate-photo {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid rgba(255,255,255,0.3);
        }
        .progress {
            height: 12px;
            border-radius: 10px;
            background: rgba(255,255,255,0.1);
        }
        .progress-bar {
            border-radius: 10px;
            background: linear-gradient(
                90deg, #28a745, #20c997
            );
        }
        .vote-count {
            font-size: 24px;
            font-weight: bold;
            color: #28a745;
        }
        .percentage {
            font-size: 14px;
            opacity: 0.7;
        }
        .leading-badge {
            background: rgba(40,167,69,0.3);
            border: 1px solid #28a745;
            color: #28a745;
            border-radius: 20px;
            padding: 3px 12px;
            font-size: 12px;
        }
        .chart-card {
            background: rgba(255,255,255,0.08);
            border-radius: 20px;
            padding: 25px;
            margin-bottom: 20px;
        }
        .total-votes-box {
            background: rgba(40,167,69,0.15);
            border: 1px solid rgba(40,167,69,0.3);
            border-radius: 15px;
            padding: 20px;
            text-align: center;
            margin-bottom: 20px;
        }
        .btn-back {
            background: transparent;
            border: 1px solid rgba(255,255,255,0.4);
            color: white;
            padding: 10px 30px;
            border-radius: 50px;
            text-decoration: none;
            display: inline-block;
            margin-top: 10px;
        }
        .btn-back:hover {
            background: rgba(255,255,255,0.1);
            color: white;
        }
        .live-dot {
            width: 10px;
            height: 10px;
            background: #28a745;
            border-radius: 50%;
            display: inline-block;
            margin-right: 5px;
            animation: blink 1s infinite;
        }
        @keyframes blink {
            0%, 100% { opacity: 1; }
            50% { opacity: 0; }
        }
    </style>
</head>
<body>
<div class="container pb-5">

    <div class="results-header">
        <img src="https://cdn-icons-png.flaticon.com/512/3097/3097144.png"
             width="50" alt="Vote"><br><br>
        <h3 class="fw-bold">Live Election Results</h3>
        <p style="opacity:0.7">
            <span class="live-dot"></span>
            Results update automatically every 30 seconds
        </p>
    </div>

    <!-- Total votes summary -->
    <div class="total-votes-box">
        <div class="row text-center">
            <div class="col-4">
                <div class="vote-count" id="totalVotes">0</div>
                <div class="percentage">Total Votes</div>
            </div>
            <div class="col-4">
                <div class="vote-count" id="totalVoters">0</div>
                <div class="percentage">Registered Voters</div>
            </div>
            <div class="col-4">
                <div class="vote-count" id="turnout">0%</div>
                <div class="percentage">Voter Turnout</div>
            </div>
        </div>
    </div>

    <!-- Results Chart -->
    <div class="chart-card">
        <h5 class="fw-bold mb-3 text-center">
            Vote Distribution
        </h5>
        <canvas id="resultsChart" height="200"></canvas>
    </div>

    <!-- Candidate results list -->
    <div id="candidateResults">

        <!-- Results load dynamically from database -->
        <!-- Placeholder cards shown below -->

        <div class="result-card">
            <div class="d-flex align-items-center gap-3 mb-3">
                <img src="https://cdn-icons-png.flaticon.com/512/3135/3135715.png"
                     class="candidate-photo" alt="Candidate">
                <div class="flex-grow-1">
                    <div class="d-flex justify-content-between">
                        <strong>Adama Barrow</strong>
                        <span class="leading-badge">
                            🏆 Leading
                        </span>
                    </div>
                    <div style="opacity:0.7; font-size:13px;">
                        National People's Party (NPP)
                    </div>
                </div>
                <div class="text-end">
                    <div class="vote-count">--</div>
                    <div class="percentage">votes</div>
                </div>
            </div>
            <div class="progress">
                <div class="progress-bar" 
                     style="width: 0%"></div>
            </div>
        </div>

        <div class="result-card">
            <div class="d-flex align-items-center gap-3 mb-3">
                <img src="https://cdn-icons-png.flaticon.com/512/3135/3135715.png"
                     class="candidate-photo" alt="Candidate">
                <div class="flex-grow-1">
                    <div class="d-flex justify-content-between">
                        <strong>Ousainou Darboe</strong>
                    </div>
                    <div style="opacity:0.7; font-size:13px;">
                        United Democratic Party (UDP)
                    </div>
                </div>
                <div class="text-end">
                    <div class="vote-count">--</div>
                    <div class="percentage">votes</div>
                </div>
            </div>
            <div class="progress">
                <div class="progress-bar"
                     style="width: 0%"></div>
            </div>
        </div>

        <div class="result-card">
            <div class="d-flex align-items-center gap-3 mb-3">
                <img src="https://cdn-icons-png.flaticon.com/512/3135/3135715.png"
                     class="candidate-photo" alt="Candidate">
                <div class="flex-grow-1">
                    <div class="d-flex justify-content-between">
                        <strong>Mama Kandeh</strong>
                    </div>
                    <div style="opacity:0.7; font-size:13px;">
                        Gambia Democratic Congress (GDC)
                    </div>
                </div>
                <div class="text-end">
                    <div class="vote-count">--</div>
                    <div class="percentage">votes</div>
                </div>
            </div>
            <div class="progress">
                <div class="progress-bar"
                     style="width: 0%"></div>
            </div>
        </div>

        <div class="result-card">
            <div class="d-flex align-items-center gap-3 mb-3">
                <img src="https://cdn-icons-png.flaticon.com/512/3135/3135715.png"
                     class="candidate-photo" alt="Candidate">
                <div class="flex-grow-1">
                    <div class="d-flex justify-content-between">
                        <strong>Halifa Sallah</strong>
                    </div>
                    <div style="opacity:0.7; font-size:13px;">
                        People's Democratic Organisation (PDOIS)
                    </div>
                </div>
                <div class="text-end">
                    <div class="vote-count">--</div>
                    <div class="percentage">votes</div>
                </div>
            </div>
            <div class="progress">
                <div class="progress-bar"
                     style="width: 0%"></div>
            </div>
        </div>

    </div>

    <div class="text-center mt-3">
        <small style="opacity:0.5">
            🔒 Results are verified and tamper-proof
        </small><br>
        <a href="index.php" class="btn-back">
            ← Back to Home
        </a>
    </div>
</div>

<script>
// Chart placeholder - will load real data from DB
const ctx = document.getElementById('resultsChart')
    .getContext('2d');
const resultsChart = new Chart(ctx, {
    type: 'doughnut',
    data: {
        labels: [
            'Adama Barrow',
            'Ousainou Darboe',
            'Mama Kandeh',
            'Halifa Sallah'
        ],
        datasets: [{
            data: [0, 0, 0, 0],
            backgroundColor: [
                '#28a745',
                '#007bff',
                '#ffc107',
                '#dc3545'
            ],
            borderWidth: 0
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                labels: { color: 'white' }
            }
        }
    }
});

// Auto refresh every 30 seconds
setTimeout(function() {
    location.reload();
}, 30000);
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>