<?php
session_start();
if(!isset($_SESSION['voter_id'])) {
    header('Location: login.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cast Your Vote</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #0a0a2e; color: white; }
        .ballot-header {
            background: rgba(255,255,255,0.1);
            border-radius: 20px;
            padding: 25px;
            text-align: center;
            margin: 20px 0;
            backdrop-filter: blur(10px);
        }
        .candidate-card {
            background: rgba(255,255,255,0.08);
            border: 2px solid rgba(255,255,255,0.15);
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 15px;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        .candidate-card:hover {
            background: rgba(40,167,69,0.2);
            border-color: #28a745;
            transform: translateY(-3px);
        }
        .candidate-card.selected {
            background: rgba(40,167,69,0.3);
            border-color: #28a745;
            box-shadow: 0 0 20px rgba(40,167,69,0.4);
        }
        .candidate-photo {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid rgba(255,255,255,0.3);
        }
        .candidate-name {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .candidate-party {
            font-size: 14px;
            opacity: 0.7;
            color: #28a745;
        }
        .select-indicator {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            border: 2px solid rgba(255,255,255,0.4);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
        }
        .selected .select-indicator {
            background: #28a745;
            border-color: #28a745;
        }
        .btn-cast {
            background-color: #28a745;
            color: white;
            padding: 15px;
            border-radius: 50px;
            font-size: 18px;
            border: none;
            width: 100%;
            margin-top: 20px;
            display: none;
        }
        .btn-cast:hover { background-color: #218838; }
        .btn-cast.show { display: block; }
        .election-badge {
            background: rgba(40,167,69,0.2);
            border: 1px solid rgba(40,167,69,0.4);
            border-radius: 20px;
            padding: 5px 15px;
            font-size: 13px;
            color: #28a745;
            display: inline-block;
            margin-bottom: 10px;
        }
        .warning-box {
            background: rgba(255,193,7,0.15);
            border: 1px solid rgba(255,193,7,0.4);
            border-radius: 10px;
            padding: 12px;
            margin-bottom: 20px;
            font-size: 13px;
            color: #ffc107;
        }
        /* Confirmation Modal */
        .modal-content {
            background: #0d1b4b;
            border: 1px solid rgba(255,255,255,0.2);
            color: white;
            border-radius: 20px;
        }
        .modal-header {
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }
        .modal-footer {
            border-top: 1px solid rgba(255,255,255,0.1);
        }
        .btn-confirm {
            background: #28a745;
            color: white;
            border: none;
            padding: 10px 30px;
            border-radius: 50px;
        }
        .btn-cancel {
            background: transparent;
            color: white;
            border: 1px solid rgba(255,255,255,0.4);
            padding: 10px 30px;
            border-radius: 50px;
        }
    </style>
</head>
<body>
<div class="container pb-5">

    <div class="ballot-header">
        <img src="https://cdn-icons-png.flaticon.com/512/3097/3097144.png"
             width="50" alt="Vote"><br><br>
        <span class="election-badge">🗳️ LIVE ELECTION</span>
        <h3 class="fw-bold mt-2">Presidential Election 2025</h3>
        <p style="opacity:0.7">
            Select ONE candidate and cast your vote
        </p>
        <small style="opacity:0.5">
            Logged in as: 
            <?php echo htmlspecialchars(
                $_SESSION['voter_name'] ?? 'Voter'
            ); ?>
        </small>
    </div>

    <?php if(isset($_SESSION['error'])): ?>
    <div class="alert alert-danger">
        <?php echo $_SESSION['error'];
        unset($_SESSION['error']); ?>
    </div>
    <?php endif; ?>

    <div class="warning-box text-center">
        ⚠️ You can only vote ONCE. 
        Please choose carefully before confirming.
    </div>

    <form id="ballotForm" 
          action="process_vote.php" method="POST">

        <!-- Candidate 1 -->
        <div class="candidate-card" 
             onclick="selectCandidate(this, 1)">
            <div class="d-flex align-items-center gap-3">
                <img src="https://cdn-icons-png.flaticon.com/512/3135/3135715.png"
                     class="candidate-photo" alt="Candidate">
                <div class="flex-grow-1">
                    <div class="candidate-name">
                        Julius Maada Bio
                    </div>
                    <div class="candidate-party">
                        🟢Sierra Leone People's Party (SLPP)
                    </div>
                </div>
                <div class="select-indicator">✓</div>
            </div>
        </div>

        <!-- Candidate 2 -->
        <div class="candidate-card"
             onclick="selectCandidate(this, 2)">
            <div class="d-flex align-items-center gap-3">
                <img src="https://cdn-icons-png.flaticon.com/512/3135/3135715.png"
                     class="candidate-photo" alt="Candidate">
                <div class="flex-grow-1">
                    <div class="candidate-name">
                       Kandeh Kolleh Yumkella
                    </div>
                    <div class="candidate-party">
                        🔵National Grand Coalition (NGC)
                    </div>
                </div>
                <div class="select-indicator">✓</div>
            </div>
        </div>

        <!-- Candidate 3 -->
        <div class="candidate-card"
             onclick="selectCandidate(this, 3)">
            <div class="d-flex align-items-center gap-3">
                <img src="https://cdn-icons-png.flaticon.com/512/3135/3135715.png"
                     class="candidate-photo" alt="Candidate">
                <div class="flex-grow-1">
                    <div class="candidate-name">
                        Charles Francis Margai
                    </div>
                    <div class="candidate-party">
                        🟡 People's Movement for Democratic Change (PMDC)
                    </div>
                </div>
                <div class="select-indicator">✓</div>
            </div>
        </div>

        <!-- Candidate 4 -->
        <div class="candidate-card"
             onclick="selectCandidate(this, 4)">
            <div class="d-flex align-items-center gap-3">
                <img src="https://cdn-icons-png.flaticon.com/512/3135/3135715.png"
                     class="candidate-photo" alt="Candidate">
                <div class="flex-grow-1">
                    <div class="candidate-name">
                        Samura Mathew Wilson Kamara
                    </div>
                    <div class="candidate-party">
                        🔴 All People's Congress (APC)
                    </div>
                </div>
                <div class="select-indicator">✓</div>
            </div>
        </div>

        <input type="hidden" name="candidate_id" 
               id="selectedCandidate" value="">

        <button type="button" class="btn-cast" 
                id="castBtn"
                onclick="showConfirmModal()">
            🗳️ Cast My Vote
        </button>
    </form>
</div>

<!-- Confirmation Modal -->
<div class="modal fade" id="confirmModal" 
     tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content p-2">
            <div class="modal-header">
                <h5 class="modal-title fw-bold">
                    ⚠️ Confirm Your Vote
                </h5>
            </div>
            <div class="modal-body text-center py-4">
                <p style="font-size:16px;">
                    You are about to vote for:
                </p>
                <h4 class="fw-bold text-success" 
                    id="confirmName"></h4>
                <p style="opacity:0.7; font-size:14px;"
                   id="confirmParty"></p>
                <hr style="border-color:rgba(255,255,255,0.2)">
                <p style="color:#ffc107; font-size:13px;">
                    ⚠️ This action CANNOT be undone. 
                    You can only vote once.
                </p>
            </div>
            <div class="modal-footer justify-content-center gap-3">
                <button class="btn-cancel" 
                        data-bs-dismiss="modal">
                    Cancel
                </button>
                <button class="btn-confirm"
                        onclick="submitVote()">
                    Yes, Cast My Vote
                </button>
            </div>
        </div>
    </div>
</div>

<script>
let selectedId = '';
let selectedName = '';
let selectedParty = '';

const candidates = {
    1: { name: 'Adama Barrow', 
         party: 'National Peoples Party (NPP)' },
    2: { name: 'Ousainou Darboe', 
         party: 'United Democratic Party (UDP)' },
    3: { name: 'Mama Kandeh', 
         party: 'Gambia Democratic Congress (GDC)' },
    4: { name: 'Halifa Sallah', 
         party: 'Peoples Democratic Organisation (PDOIS)' }
};

function selectCandidate(card, id) {
    document.querySelectorAll('.candidate-card')
        .forEach(c => c.classList.remove('selected'));
    card.classList.add('selected');
    selectedId = id;
    selectedName = candidates[id].name;
    selectedParty = candidates[id].party;
    document.getElementById('selectedCandidate').value = id;
    document.getElementById('castBtn').classList.add('show');
}

function showConfirmModal() {
    if(!selectedId) return;
    document.getElementById('confirmName').textContent = 
        selectedName;
    document.getElementById('confirmParty').textContent = 
        selectedParty;
    new bootstrap.Modal(
        document.getElementById('confirmModal')
    ).show();
}

function submitVote() {
    document.getElementById('ballotForm').submit();
}
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>