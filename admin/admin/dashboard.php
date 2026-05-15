<?php
session_start();
if(!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #0a0a2e; color: white; }
        .sidebar {
            background: rgba(255,255,255,0.08);
            min-height: 100vh;
            padding: 20px;
            position: fixed;
            width: 240px;
            left: 0;
            top: 0;
            overflow-y: auto;
        }
        .main-content {
            margin-left: 240px;
            padding: 20px;
        }
        .nav-link {
            color: rgba(255,255,255,0.7);
            padding: 12px 15px;
            border-radius: 10px;
            margin-bottom: 5px;
            display: block;
            text-decoration: none;
            transition: all 0.3s;
        }
        .nav-link:hover, .nav-link.active {
            background: rgba(0,123,255,0.3);
            color: white;
        }
        .stat-card {
            background: rgba(255,255,255,0.08);
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 20px;
            border: 1px solid rgba(255,255,255,0.1);
        }
        .stat-number {
            font-size: 36px;
            font-weight: bold;
            color: #28a745;
        }
        .section-card {
            background: rgba(255,255,255,0.08);
            border-radius: 15px;
            padding: 25px;
            margin-bottom: 25px;
            border: 1px solid rgba(255,255,255,0.1);
        }
        .form-control, .form-select {
            background: rgba(255,255,255,0.15);
            border: 1px solid rgba(255,255,255,0.3);
            color: white;
            border-radius: 10px;
            padding: 10px;
        }
        .form-control:focus, .form-select:focus {
            background: rgba(255,255,255,0.25);
            color: white;
            border-color: #007bff;
            box-shadow: none;
        }
        .form-control::placeholder {
            color: rgba(255,255,255,0.5);
        }
        .form-select option {
            background: #0a0a2e;
            color: white;
        }
        label {
            color: rgba(255,255,255,0.8);
            margin-bottom: 5px;
        }
        .btn-add {
            background: #28a745;
            color: white;
            border: none;
            padding: 10px 25px;
            border-radius: 50px;
            font-size: 14px;
        }
        .btn-add:hover {
            background: #218838;
            color: white;
        }
        .btn-edit {
            background: #007bff;
            color: white;
            border: none;
            padding: 6px 15px;
            border-radius: 20px;
            font-size: 13px;
        }
        .btn-delete {
            background: #dc3545;
            color: white;
            border: none;
            padding: 6px 15px;
            border-radius: 20px;
            font-size: 13px;
        }
        .candidate-table {
            width: 100%;
            border-collapse: collapse;
        }
        .candidate-table th {
            background: rgba(0,123,255,0.2);
            padding: 12px;
            text-align: left;
            font-size: 13px;
            opacity: 0.8;
        }
        .candidate-table td {
            padding: 12px;
            border-bottom: 1px solid rgba(255,255,255,0.08);
            font-size: 14px;
            vertical-align: middle;
        }
        .candidate-table tr:hover {
            background: rgba(255,255,255,0.05);
        }
        .candidate-photo-small {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid rgba(255,255,255,0.2);
        }
        .status-active {
            background: rgba(40,167,69,0.2);
            color: #28a745;
            border: 1px solid rgba(40,167,69,0.4);
            border-radius: 20px;
            padding: 3px 12px;
            font-size: 12px;
        }
        .status-closed {
            background: rgba(220,53,69,0.2);
            color: #dc3545;
            border: 1px solid rgba(220,53,69,0.4);
            border-radius: 20px;
            padding: 3px 12px;
            font-size: 12px;
        }
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
        .btn-save {
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
        .top-bar {
            background: rgba(255,255,255,0.08);
            border-radius: 15px;
            padding: 15px 20px;
            margin-bottom: 25px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .logout-btn {
            background: rgba(220,53,69,0.2);
            color: #dc3545;
            border: 1px solid rgba(220,53,69,0.4);
            padding: 8px 20px;
            border-radius: 20px;
            text-decoration: none;
            font-size: 14px;
        }
        .logout-btn:hover {
            background: rgba(220,53,69,0.4);
            color: white;
        }
        /* Mobile responsive */
        @media(max-width: 768px) {
            .sidebar {
                position: relative;
                width: 100%;
                min-height: auto;
            }
            .main-content { margin-left: 0; }
            .sidebar-links {
                display: flex;
                flex-wrap: wrap;
                gap: 5px;
            }
            .nav-link { padding: 8px 12px; font-size: 13px; }
        }
    </style>
</head>
<body>

<!-- Sidebar -->
<div class="sidebar">
    <div class="text-center mb-4">
        <img src="https://cdn-icons-png.flaticon.com/512/3097/3097144.png"
             width="50" alt="Admin">
        <div class="fw-bold mt-2">Admin Panel</div>
        <small style="opacity:0.5">Election Management</small>
    </div>
    <div class="sidebar-links">
        <a href="#overview" class="nav-link active">
            📊 Overview
        </a>
        <a href="#candidates" class="nav-link">
            👤 Candidates
        </a>
        <a href="#voters" class="nav-link">
            🗳️ Voters
        </a>
        <a href="#election" class="nav-link">
            ⚙️ Election Control
        </a>
        <a href="#audit" class="nav-link">
            📋 Audit Log
        </a>
        <a href="../results.php" class="nav-link"
           target="_blank">
            📈 Live Results
        </a>
        <a href="logout.php" class="nav-link"
           style="color:#dc3545; margin-top:20px;">
            🚪 Logout
        </a>
    </div>
</div>

<!-- Main Content -->
<div class="main-content">

    <!-- Top Bar -->
    <div class="top-bar">
        <div>
            <strong>Welcome, Admin</strong>
            <small style="opacity:0.6; margin-left:10px;">
                <?php echo date('D d M Y, H:i'); ?>
            </small>
        </div>
        <a href="logout.php" class="logout-btn">
            Logout
        </a>
    </div>

    <!-- OVERVIEW STATS -->
    <div id="overview">
        <h5 class="fw-bold mb-3">📊 Overview</h5>
        <div class="row">
            <div class="col-md-3 col-6">
                <div class="stat-card text-center">
                    <div class="stat-number" id="totalVoters">
                        --
                    </div>
                    <div style="opacity:0.7">
                        Registered Voters
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="stat-card text-center">
                    <div class="stat-number" id="totalVoted">
                        --
                    </div>
                    <div style="opacity:0.7">Votes Cast</div>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="stat-card text-center">
                    <div class="stat-number" id="totalCandidates">
                        --
                    </div>
                    <div style="opacity:0.7">Candidates</div>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="stat-card text-center">
                    <div class="stat-number" id="turnout">
                        --%
                    </div>
                    <div style="opacity:0.7">Turnout</div>
                </div>
            </div>
        </div>
    </div>

    <!-- CANDIDATES SECTION -->
    <div class="section-card" id="candidates">
        <div class="d-flex justify-content-between
                    align-items-center mb-4">
            <h5 class="fw-bold mb-0">
                👤 Manage Candidates
            </h5>
            <button class="btn-add"
                    onclick="openAddModal()">
                + Add Candidate
            </button>
        </div>

        <!-- Candidates Table -->
        <div class="table-responsive">
            <table class="candidate-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Photo</th>
                        <th>Full Name</th>
                        <th>Party</th>
                        <th>Election</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="candidatesTableBody">
                    <tr>
                        <td colspan="6"
                            class="text-center"
                            style="opacity:0.5;
                                   padding:30px;">
                            Loading candidates...
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- ELECTION CONTROL -->
    <div class="section-card" id="election">
        <h5 class="fw-bold mb-4">
            ⚙️ Election Control
        </h5>
        <div class="row">
            <div class="col-md-6">
                <div class="stat-card">
                    <div class="mb-2">Election Status</div>
                    <span class="status-active"
                          id="electionStatus">
                        ● Active
                    </span>
                    <div class="mt-3 d-flex gap-2">
                        <button class="btn-add"
                                onclick="controlElection('open')">
                            ▶ Open Voting
                        </button>
                        <button class="btn-delete"
                                style="padding:10px 20px;
                                       border-radius:50px;"
                                onclick="controlElection('close')">
                            ■ Close Voting
                        </button>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="stat-card">
                    <div class="mb-2">Export Data</div>
                    <div class="d-flex gap-2 flex-wrap">
                        <a href="export_voters.php"
                           class="btn-add"
                           style="text-decoration:none;
                                  padding:10px 20px;">
                            📥 Export Voters CSV
                        </a>
                        <a href="export_results.php"
                           class="btn-edit"
                           style="text-decoration:none;
                                  padding:10px 20px;
                                  border-radius:50px;">
                            📊 Export Results CSV
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- VOTERS TABLE -->
    <div class="section-card" id="voters">
        <h5 class="fw-bold mb-4">
            🗳️ Registered Voters
        </h5>
        <div class="table-responsive">
            <table class="candidate-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Full Name</th>
                        <th>NIN</th>
                        <th>Phone</th>
                        <th>Voted?</th>
                        <th>Registered</th>
                    </tr>
                </thead>
                <tbody id="votersTableBody">
                    <tr>
                        <td colspan="6"
                            class="text-center"
                            style="opacity:0.5;
                                   padding:30px;">
                            Loading voters...
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- AUDIT LOG -->
    <div class="section-card" id="audit">
        <h5 class="fw-bold mb-4">📋 Audit Log</h5>
        <div class="table-responsive">
            <table class="candidate-table">
                <thead>
                    <tr>
                        <th>Time</th>
                        <th>Action</th>
                        <th>Voter</th>
                        <th>IP Address</th>
                    </tr>
                </thead>
                <tbody id="auditTableBody">
                    <tr>
                        <td colspan="4"
                            class="text-center"
                            style="opacity:0.5;
                                   padding:30px;">
                            Loading audit log...
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

</div>

<!-- ADD CANDIDATE MODAL -->
<div class="modal fade" id="addModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content p-2">
            <div class="modal-header">
                <h5 class="modal-title fw-bold">
                    ➕ Add New Candidate
                </h5>
                <button type="button"
                        class="btn-close btn-close-white"
                        data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="addCandidateForm"
                      enctype="multipart/form-data">
                    <div class="mb-3">
                        <label>Full Name</label>
                        <input type="text"
                               name="full_name"
                               id="add_full_name"
                               class="form-control"
                               placeholder="Candidate full name"
                               required>
                    </div>
                    <div class="mb-3">
                        <label>Political Party</label>
                        <input type="text"
                               name="party"
                               id="add_party"
                               class="form-control"
                               placeholder="Party name"
                               required>
                    </div>
                    <div class="mb-3">
                        <label>Election</label>
                        <input type="text"
                               name="election"
                               id="add_election"
                               class="form-control"
                               placeholder="e.g. Presidential Election 2025"
                               required>
                    </div>
                    <div class="mb-3">
                        <label>Candidate Photo</label>
                        <input type="file"
                               name="photo"
                               id="add_photo"
                               class="form-control"
                               accept="image/*"
                               onchange="previewAddPhoto(this)">
                        <img id="addPhotoPreview"
                             style="width:80px;
                                    height:80px;
                                    border-radius:50%;
                                    object-fit:cover;
                                    display:none;
                                    margin-top:10px;
                                    border:3px solid #28a745;">
                    </div>
                    <div class="mb-3">
                        <label>Bio / Description</label>
                        <textarea name="bio"
                                  id="add_bio"
                                  class="form-control"
                                  rows="3"
                                  placeholder="Short candidate description">
                        </textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer justify-content-center gap-3">
                <button class="btn-cancel"
                        data-bs-dismiss="modal">
                    Cancel
                </button>
                <button class="btn-save"
                        onclick="saveCandidate()">
                    Save Candidate
                </button>
            </div>
        </div>
    </div>
</div>

<!-- EDIT CANDIDATE MODAL -->
<div class="modal fade" id="editModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content p-2">
            <div class="modal-header">
                <h5 class="modal-title fw-bold">
                    ✏️ Edit Candidate
                </h5>
                <button type="button"
                        class="btn-close btn-close-white"
                        data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="editCandidateForm"
                      enctype="multipart/form-data">
                    <input type="hidden"
                           name="candidate_id"
                           id="edit_id">
                    <div class="mb-3">
                        <label>Full Name</label>
                        <input type="text"
                               name="full_name"
                               id="edit_full_name"
                               class="form-control"
                               required>
                    </div>
                    <div class="mb-3">
                        <label>Political Party</label>
                        <input type="text"
                               name="party"
                               id="edit_party"
                               class="form-control"
                               required>
                    </div>
                    <div class="mb-3">
                        <label>Election</label>
                        <input type="text"
                               name="election"
                               id="edit_election"
                               class="form-control"
                               required>
                    </div>
                    <div class="mb-3">
                        <label>
                            Update Photo (optional)
                        </label>
                        <input type="file"
                               name="photo"
                               id="edit_photo"
                               class="form-control"
                               accept="image/*"
                               onchange="previewEditPhoto(this)">
                        <img id="editPhotoPreview"
                             style="width:80px;
                                    height:80px;
                                    border-radius:50%;
                                    object-fit:cover;
                                    margin-top:10px;
                                    border:3px solid #007bff;">
                    </div>
                    <div class="mb-3">
                        <label>Bio / Description</label>
                        <textarea name="bio"
                                  id="edit_bio"
                                  class="form-control"
                                  rows="3">
                        </textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer justify-content-center gap-3">
                <button class="btn-cancel"
                        data-bs-dismiss="modal">
                    Cancel
                </button>
                <button class="btn-save"
                        onclick="updateCandidate()">
                    Update Candidate
                </button>
            </div>
        </div>
    </div>
</div>

<!-- DELETE CONFIRM MODAL -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content p-2 text-center">
            <div class="modal-header justify-content-center">
                <h5 class="modal-title fw-bold text-danger">
                    🗑️ Delete Candidate
                </h5>
            </div>
            <div class="modal-body py-4">
                <p>Are you sure you want to delete</p>
                <h5 class="fw-bold text-danger"
                    id="deleteNameDisplay"></h5>
                <p style="opacity:0.6; font-size:13px;">
                    This action cannot be undone
                </p>
                <input type="hidden" id="deleteId">
            </div>
            <div class="modal-footer justify-content-center gap-3">
                <button class="btn-cancel"
                        data-bs-dismiss="modal">
                    Cancel
                </button>
                <button class="btn-delete"
                        style="padding:10px 30px;
                               border-radius:50px;"
                        onclick="confirmDelete()">
                    Yes, Delete
                </button>
            </div>
        </div>
    </div>
</div>

<script>
// ---- CANDIDATE MANAGEMENT ----

function openAddModal() {
    new bootstrap.Modal(
        document.getElementById('addModal')
    ).show();
}

function previewAddPhoto(input) {
    if(input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            var preview = document.getElementById(
                'addPhotoPreview'
            );
            preview.src = e.target.result;
            preview.style.display = 'block';
        }
        reader.readAsDataURL(input.files[0]);
    }
}

function previewEditPhoto(input) {
    if(input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById(
                'editPhotoPreview'
            ).src = e.target.result;
        }
        reader.readAsDataURL(input.files[0]);
    }
}

function saveCandidate() {
    var name = document.getElementById(
        'add_full_name'
    ).value;
    var party = document.getElementById(
        'add_party'
    ).value;
    var election = document.getElementById(
        'add_election'
    ).value;

    if(!name || !party || !election) {
        alert('Please fill in all required fields');
        return;
    }

    var formData = new FormData(
        document.getElementById('addCandidateForm')
    );

    fetch('../api/add_candidate.php', {
        method: 'POST',
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        if(data.success) {
            bootstrap.Modal.getInstance(
                document.getElementById('addModal')
            ).hide();
            loadCandidates();
            showAlert(
                'Candidate added successfully!',
                'success'
            );
        } else {
            showAlert(data.message, 'danger');
        }
    });
}

function openEditModal(id, name, party, election,
                       photo, bio) {
    document.getElementById('edit_id').value = id;
    document.getElementById('edit_full_name').value = name;
    document.getElementById('edit_party').value = party;
    document.getElementById('edit_election').value = election;
    document.getElementById('edit_bio').value = bio;
    document.getElementById('editPhotoPreview').src = photo;
    new bootstrap.Modal(
        document.getElementById('editModal')
    ).show();
}

function updateCandidate() {
    var formData = new FormData(
        document.getElementById('editCandidateForm')
    );

    fetch('../api/edit_candidate.php', {
        method: 'POST',
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        if(data.success) {
            bootstrap.Modal.getInstance(
                document.getElementById('editModal')
            ).hide();
            loadCandidates();
            showAlert(
                'Candidate updated successfully!',
                'success'
            );
        } else {
            showAlert(data.message, 'danger');
        }
    });
}

function openDeleteModal(id, name) {
    document.getElementById('deleteId').value = id;
    document.getElementById(
        'deleteNameDisplay'
    ).textContent = name;
    new bootstrap.Modal(
        document.getElementById('deleteModal')
    ).show();
}

function confirmDelete() {
    var id = document.getElementById('deleteId').value;
    fetch('../api/delete_candidate.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ id: id })
    })
    .then(res => res.json())
    .then(data => {
        if(data.success) {
            bootstrap.Modal.getInstance(
                document.getElementById('deleteModal')
            ).hide();
            loadCandidates();
            showAlert(
                'Candidate deleted successfully!',
                'success'
            );
        }
    });
}

function loadCandidates() {
    fetch('../api/get_candidates.php')
    .then(res => res.json())
    .then(data => {
        var tbody = document.getElementById(
            'candidatesTableBody'
        );
        if(data.length === 0) {
            tbody.innerHTML = `
                <tr>
                    <td colspan="6"
                        class="text-center"
                        style="opacity:0.5; padding:30px;">
                        No candidates added yet
                    </td>
                </tr>`;
            return;
        }
        tbody.innerHTML = data.map((c, i) => `
            <tr>
                <td>${i + 1}</td>
                <td>
                    <img src="${c.photo_url ||
                        'https://cdn-icons-png.flaticon.com/512/3135/3135715.png'}"
                         class="candidate-photo-small"
                         alt="${c.full_name}">
                </td>
                <td><strong>${c.full_name}</strong></td>
                <td style="opacity:0.7">${c.party}</td>
                <td style="opacity:0.7">${c.election}</td>
                <td>
                    <button class="btn-edit me-1"
                        onclick="openEditModal(
                            '${c.id}',
                            '${c.full_name}',
                            '${c.party}',
                            '${c.election}',
                            '${c.photo_url}',
                            '${c.bio}')">
                        ✏️ Edit
                    </button>
                    <button class="btn-delete"
                        onclick="openDeleteModal(
                            '${c.id}',
                            '${c.full_name}')">
                        🗑️ Delete
                    </button>
                </td>
            </tr>
        `).join('');
    });
}

function controlElection(action) {
    if(!confirm(
        'Are you sure you want to ' + action +
        ' the election?'
    )) return;

    fetch('../api/control_election.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ action: action })
    })
    .then(res => res.json())
    .then(data => {
        if(data.success) {
            showAlert(
                'Election ' + action + 'ed successfully!',
                'success'
            );
            document.getElementById(
                'electionStatus'
            ).textContent = action === 'open' ?
                '● Active' : '■ Closed';
        }
    });
}

function showAlert(message, type) {
    var div = document.createElement('div');
    div.className = `alert alert-${type}
        position-fixed top-0 start-50
        translate-middle-x mt-3`;
    div.style.zIndex = '9999';
    div.textContent = message;
    document.body.appendChild(div);
    setTimeout(() => div.remove(), 3000);
}

// Load data on page load
loadCandidates();
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>