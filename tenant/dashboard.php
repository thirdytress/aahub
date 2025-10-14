<?php
session_start();
require_once "../classes/database.php";

if (!isset($_SESSION['user_id']) || ($_SESSION['role'] ?? '') !== 'tenant') {
    header("Location: ../actions/login.php");
    exit();
}

$db = new Database();
$tenant = $db->getTenantProfile($_SESSION['user_id']);
$fullname = $tenant['firstname'] . ' ' . $tenant['lastname'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Tenant Dashboard | ApartmentHub</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

<style>
    :root {
        --primary-dark: #2c3e50;
        --primary-blue: #3498db;
        --accent-gold: #d4af37;
        --luxury-gold: #c9a961;
        --earth-brown: #8b7355;
        --soft-gray: #95a5a6;
        --deep-navy: #1a252f;
    }
    body {
        font-family: 'Poppins', sans-serif;
        background: linear-gradient(135deg, #f5f1e8 0%, #e8dcc8 50%, #f5f1e8 100%);
        min-height: 100vh;
        position: relative;
        overflow-x: hidden;
    }
    body::before {
        content: '';
        position: fixed;
        top: 0; left: 0;
        width: 100%; height: 100%;
        background-image: 
            repeating-linear-gradient(90deg, rgba(212,175,55,0.03) 0px, transparent 1px, transparent 40px, rgba(212,175,55,0.03) 41px),
            repeating-linear-gradient(0deg, rgba(212,175,55,0.03) 0px, transparent 1px, transparent 40px, rgba(212,175,55,0.03) 41px);
        z-index: 0;
        pointer-events: none;
    }
    .navbar {
        background: linear-gradient(135deg, var(--deep-navy) 0%, var(--primary-dark) 100%) !important;
        backdrop-filter: blur(10px);
        box-shadow: 0 4px 30px rgba(0,0,0,0.3);
        border-bottom: 3px solid var(--accent-gold);
        padding: 1rem 0;
        position: relative;
        z-index: 1000;
    }
    .navbar-brand {
        font-size: 1.8rem;
        font-weight: 700;
        color: white !important;
        letter-spacing: 1px;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .navbar-brand i { color: var(--accent-gold); animation: pulse 2s infinite; }
    @keyframes pulse { 0%,100%{transform:scale(1);}50%{transform:scale(1.1);} }
    .btn-logout {
        background: linear-gradient(135deg, var(--accent-gold) 0%, var(--luxury-gold) 100%);
        border-radius: 30px;
        font-weight: 700;
        color: var(--deep-navy);
        padding: 10px 30px;
        text-transform: uppercase;
        box-shadow: 0 4px 20px rgba(212,175,55,0.4);
        transition: all 0.4s ease;
    }
    .btn-logout:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 30px rgba(212,175,55,0.6);
        background: linear-gradient(135deg, var(--luxury-gold) 0%, var(--accent-gold) 100%);
    }
    .welcome {
        margin-top: 60px;
        margin-bottom: 70px;
        animation: fadeInDown 1s ease;
        position: relative;
        z-index: 1;
    }
    @keyframes fadeInDown { from{opacity:0;transform:translateY(-50px);}to{opacity:1;transform:translateY(0);} }
    .welcome h2 { font-size:3rem; font-weight:800; color:var(--primary-dark); text-shadow:2px 2px 4px rgba(0,0,0,0.1); }
    .welcome p { font-size:1.2rem; color:var(--earth-brown); font-weight:500; margin-top:10px; }
    .card {
        border:none; border-radius:20px;
        transition: all 0.5s ease;
        box-shadow: 0 20px 60px rgba(0,0,0,0.15), inset 0 1px 0 rgba(255,255,255,0.6);
        background: linear-gradient(145deg, #ffffff 0%, #f8f5f0 100%);
        position:relative;
        overflow:hidden;
    }
    .card:hover { transform: translateY(-10px) scale(1.03); }
    .card-body { display:flex; flex-direction:column; align-items:center; text-align:center; padding:2rem; }
    .icon-container { width:100px; height:100px; display:flex; align-items:center; justify-content:center; margin-bottom:1rem; }
    .card-title { font-size:1.5rem; font-weight:700; margin-bottom:1rem; color:var(--primary-dark); }
    .card-text { font-size:1rem; color:var(--earth-brown); margin-bottom:1rem; }
    .btn-card { border-radius:25px; padding:10px 20px; font-weight:700; text-transform:uppercase; }

    footer {
      background: linear-gradient(135deg, var(--deep-navy) 0%, var(--primary-dark) 100%);
      color: white;
      padding: 30px 20px;
      text-align: center;
      margin-top: 100px;
      border-top: 3px solid var(--accent-gold);
      box-shadow: 0 -4px 30px rgba(0,0,0,0.3);
    }

    footer p {
      margin-bottom: 0;
      font-weight: 500;
      letter-spacing: 0.5px;
    }
</style>
</head>
<body>

<nav class="navbar navbar-expand-lg">
  <div class="container">
    <a class="navbar-brand" href="#"><i class="bi bi-building-fill"></i> ApartmentHub</a>
    <a href="../logout.php" class="btn btn-logout"><i class="bi bi-box-arrow-right me-2"></i>Logout</a>
  </div>
</nav>

<div class="container text-center">
    <div class="welcome">
        <h2>Hello, <?= htmlspecialchars($fullname) ?> 👋</h2>
        <p>Your complete apartment management dashboard</p>
    </div>

    <div class="row justify-content-center g-4 mt-4">

        <!-- Available Apartments -->
        <div class="col-md-6 col-lg-3">
            <div class="card h-100">
                <div class="card-body">
                    <div class="icon-container"><i class="bi bi-buildings" style="font-size:3rem;color:#3498db;"></i></div>
                    <h5 class="card-title">Available Apartments</h5>
                    <p class="card-text">Discover your perfect living space</p>
                    <a href="view_apartments.php" class="btn btn-primary btn-card w-100">Browse Units</a>
                </div>
            </div>
        </div>

        <!-- My Applications -->
        <div class="col-md-6 col-lg-3">
            <div class="card h-100">
                <div class="card-body">
                    <div class="icon-container"><i class="bi bi-file-earmark-text-fill" style="font-size:3rem;color:#d4af37;"></i></div>
                    <h5 class="card-title">My Applications</h5>
                    <p class="card-text">Track your rental applications</p>
                    <a href="my_applications.php" class="btn btn-warning btn-card w-100">View Applications</a>
                </div>
            </div>
        </div>

        <!-- My Leases -->
        <div class="col-md-6 col-lg-3">
            <div class="card h-100">
                <div class="card-body">
                    <div class="icon-container"><i class="bi bi-key-fill" style="font-size:3rem;color:#8b7355;"></i></div>
                    <h5 class="card-title">My Leases</h5>
                    <p class="card-text">Access your current lease agreements</p>
                    <a href="my_leases.php" class="btn btn-success btn-card w-100">View Leases</a>
                </div>
            </div>
        </div>

        <!-- Update Profile -->
        <div class="col-md-6 col-lg-3">
            <div class="card h-100">
                <div class="card-body">
                    <div class="icon-container"><i class="bi bi-person-fill-gear" style="font-size:3rem;color:#95a5a6;"></i></div>
                    <h5 class="card-title">Update Profile</h5>
                    <p class="card-text">Manage your personal information</p>
                    <a href="update_profile.php" class="btn btn-secondary btn-card w-100">Edit Profile</a>
                </div>
            </div>
        </div>

        <footer>
  <p class="mb-0">&copy; 2025 ApartmentHub. All rights reserved.</p>
</footer>

    </div>
</div>



<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
