<?php
require_once "classes/database.php";
$db = new Database();
$conn = $db->connect();

// Fetch only available apartments
$stmt = $conn->prepare("SELECT * FROM apartments WHERE Status = 'Available' ORDER BY DateAdded DESC");
$stmt->execute();
$apartments = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>ApartmentHub</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
  <style>
    :root {
      --primary-dark: #2c3e50;
      --primary-blue: #3498db;
      --accent-gold: #d4af37;
      --warm-beige: #f5f1e8;
      --soft-gray: #95a5a6;
      --deep-navy: #1a252f;
      --luxury-gold: #c9a961;
      --earth-brown: #8b7355;
    }

    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      background: linear-gradient(135deg, #f5f1e8 0%, #e8dcc8 50%, #f5f1e8 100%);
      font-family: 'Poppins', sans-serif;
      min-height: 100vh;
      position: relative;
      overflow-x: hidden;
    }

    body::before {
      content: '';
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background-image: 
        repeating-linear-gradient(90deg, rgba(212, 175, 55, 0.03) 0px, transparent 1px, transparent 40px, rgba(212, 175, 55, 0.03) 41px),
        repeating-linear-gradient(0deg, rgba(212, 175, 55, 0.03) 0px, transparent 1px, transparent 40px, rgba(212, 175, 55, 0.03) 41px);
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

    .navbar::after {
      content: '';
      position: absolute;
      bottom: -3px;
      left: 0;
      width: 100%;
      height: 3px;
      background: linear-gradient(90deg, transparent, var(--luxury-gold), transparent);
      animation: shimmer 3s infinite;
    }

    @keyframes shimmer {
      0%, 100% { opacity: 0.5; }
      50% { opacity: 1; }
    }

    .navbar-brand {
      font-size: 1.8rem;
      font-weight: 700;
      color: white !important;
      letter-spacing: 1px;
      text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
    }

    .navbar-toggler {
      border-color: var(--accent-gold);
    }

    .navbar-toggler-icon {
      filter: brightness(0) invert(1);
    }

    .nav-link {
      color: rgba(255,255,255,0.8) !important;
      font-weight: 500;
      transition: all 0.3s ease;
      position: relative;
      padding: 0.5rem 1rem !important;
    }

    .nav-link::after {
      content: '';
      position: absolute;
      bottom: 0;
      left: 50%;
      transform: translateX(-50%);
      width: 0;
      height: 2px;
      background: var(--accent-gold);
      transition: width 0.3s ease;
    }

    .nav-link:hover::after,
    .nav-link.active::after {
      width: 80%;
    }

    .nav-link:hover,
    .nav-link.active {
      color: var(--accent-gold) !important;
    }

    .hero {
      text-align: center;
      padding: 120px 20px;
      position: relative;
      z-index: 1;
      animation: fadeIn 1s ease;
    }

    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(-20px); }
      to { opacity: 1; transform: translateY(0); }
    }

    .hero h1 {
      font-weight: 800;
      font-size: 3.5rem;
      color: var(--primary-dark);
      margin-bottom: 20px;
      text-shadow: 2px 2px 4px rgba(0,0,0,0.1);
      position: relative;
      display: inline-block;
    }

    .hero h1::after {
      content: '';
      position: absolute;
      bottom: -10px;
      left: 50%;
      transform: translateX(-50%);
      width: 150px;
      height: 4px;
      background: linear-gradient(90deg, transparent, var(--accent-gold), transparent);
      border-radius: 2px;
    }

    .hero p {
      font-size: 1.3rem;
      color: var(--earth-brown);
      font-weight: 500;
      margin-top: 30px;
      margin-bottom: 30px;
    }

    .hero .btn {
      background: linear-gradient(135deg, var(--accent-gold) 0%, var(--luxury-gold) 100%);
      border: none;
      color: var(--deep-navy);
      padding: 15px 50px;
      border-radius: 30px;
      font-weight: 700;
      letter-spacing: 1px;
      transition: all 0.4s ease;
      box-shadow: 0 8px 25px rgba(212, 175, 55, 0.4);
      text-transform: uppercase;
      font-size: 1rem;
    }

    .hero .btn:hover {
      transform: translateY(-5px);
      box-shadow: 0 15px 40px rgba(212, 175, 55, 0.6);
      background: linear-gradient(135deg, var(--luxury-gold) 0%, var(--accent-gold) 100%);
    }

    .container.mt-5 {
      position: relative;
      z-index: 1;
    }

    .container.mt-5 h2 {
      font-weight: 700;
      color: var(--primary-dark);
      margin-bottom: 40px;
      font-size: 2.5rem;
      position: relative;
      display: inline-block;
    }

    .container.mt-5 h2::after {
      content: '';
      position: absolute;
      bottom: -10px;
      left: 0;
      width: 100px;
      height: 4px;
      background: var(--accent-gold);
      border-radius: 2px;
    }

    .apartment-card {
      border: none;
      border-radius: 25px;
      transition: all 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275);
      box-shadow: 0 20px 60px rgba(0,0,0,0.15);
      background: linear-gradient(145deg, #ffffff 0%, #f8f5f0 100%);
      overflow: hidden;
      position: relative;
      border: 2px solid rgba(212, 175, 55, 0.2);
    }

    .apartment-card::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      height: 6px;
      background: linear-gradient(90deg, var(--primary-dark) 0%, var(--primary-blue) 50%, var(--accent-gold) 100%);
      transform: scaleX(0);
      transition: transform 0.5s ease;
      z-index: 1;
    }

    .apartment-card:hover::before {
      transform: scaleX(1);
    }

    .apartment-card:hover {
      transform: translateY(-15px) scale(1.02);
      box-shadow: 0 30px 80px rgba(0,0,0,0.25);
    }

    .apartment-card img {
      height: 200px;
      object-fit: cover;
      transition: transform 0.5s ease;
    }

    .apartment-card:hover img {
      transform: scale(1.1);
    }

    .card-body {
      padding: 2rem;
    }

    .card-title {
      font-size: 1.4rem;
      font-weight: 700;
      color: var(--primary-dark);
      margin-bottom: 1rem;
    }

    .card-text {
      color: var(--earth-brown);
      font-weight: 500;
      line-height: 1.6;
    }

    .apartment-card .btn {
      background: linear-gradient(135deg, var(--primary-blue) 0%, var(--primary-dark) 100%);
      border: none;
      color: white;
      padding: 12px 30px;
      border-radius: 20px;
      font-weight: 600;
      transition: all 0.4s ease;
      box-shadow: 0 5px 20px rgba(52, 152, 219, 0.4);
      margin-top: auto;
    }

    .apartment-card .btn:hover {
      transform: translateY(-3px);
      box-shadow: 0 8px 30px rgba(52, 152, 219, 0.6);
    }

    .modal-content {
      border-radius: 25px;
      border: none;
      overflow: hidden;
      box-shadow: 0 20px 60px rgba(0,0,0,0.3);
    }

    .modal-header {
      background: linear-gradient(135deg, var(--deep-navy) 0%, var(--primary-dark) 100%);
      color: #fff;
      padding: 2rem;
      border-bottom: 3px solid var(--accent-gold);
    }

    .modal-title {
      font-weight: 700;
      font-size: 1.5rem;
      letter-spacing: 0.5px;
    }

    .modal-body {
      padding: 2.5rem;
      background: var(--warm-beige);
    }

    .form-label, label {
      color: var(--primary-dark);
      font-weight: 600;
      margin-bottom: 0.5rem;
    }

    .form-control, .form-select {
      border: 2px solid rgba(212, 175, 55, 0.3);
      border-radius: 15px;
      padding: 12px 20px;
      transition: all 0.3s ease;
      background: white;
    }

    .form-control:focus, .form-select:focus {
      box-shadow: 0 0 0 0.2rem rgba(212, 175, 55, 0.25);
      border-color: var(--accent-gold);
      background: white;
    }

    .modal-body .btn-primary {
      background: linear-gradient(135deg, var(--accent-gold) 0%, var(--luxury-gold) 100%);
      border: none;
      color: var(--deep-navy);
      padding: 15px;
      border-radius: 20px;
      font-weight: 700;
      letter-spacing: 1px;
      transition: all 0.4s ease;
      box-shadow: 0 5px 20px rgba(212, 175, 55, 0.4);
      text-transform: uppercase;
    }

    .modal-body .btn-primary:hover {
      transform: translateY(-3px);
      box-shadow: 0 8px 30px rgba(212, 175, 55, 0.6);
      background: linear-gradient(135deg, var(--luxury-gold) 0%, var(--accent-gold) 100%);
    }

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

    /* OTP Overlay Custom Style */
    #otpOverlay {
      display: none;
      position: absolute;
      top: 0; left: 0; right: 0; bottom: 0;
      z-index: 1056;
    }
    .otp-backdrop {
      position: absolute;
      top: 0; left: 0; right: 0; bottom: 0;
      background: rgba(0,0,0,0.5);
      z-index: 1;
    }
    .otp-center-box {
      position: absolute;
      top: 50%; left: 50%;
      transform: translate(-50%, -50%);
      z-index: 2;
      min-width: 320px;
      max-width: 90vw;
    }
    #otpOverlay .modal-content {
      box-shadow: 0 0 40px 10px rgba(212, 175, 55, 0.4);
      border: 2px solid var(--accent-gold);
    }

    .floating-decoration {
      position: fixed;
      pointer-events: none;
      z-index: 0;
    }

    .deco-1 {
      top: 10%;
      left: 5%;
      width: 150px;
      height: 150px;
      background: radial-gradient(circle, rgba(212, 175, 55, 0.1), transparent);
      border-radius: 50%;
      animation: float 6s ease-in-out infinite;
    }

    .deco-2 {
      bottom: 20%;
      right: 8%;
      width: 200px;
      height: 200px;
      background: radial-gradient(circle, rgba(52, 152, 219, 0.1), transparent);
      border-radius: 50%;
      animation: float 8s ease-in-out infinite reverse;
    }

    @keyframes float {
      0%, 100% { transform: translateY(0px); }
      50% { transform: translateY(-30px); }
    }

    @media (max-width: 768px) {
      .hero h1 {
        font-size: 2.5rem;
      }

      .hero p {
        font-size: 1.1rem;
      }

      .container.mt-5 h2 {
        font-size: 2rem;
      }
    }
  </style>
</head>
<body>

<div class="floating-decoration deco-1"></div>
<div class="floating-decoration deco-2"></div>

<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg bg-white sticky-top">
  <div class="container">
    <a class="navbar-brand fw-bold text-primary" href="#">ApartmentHub</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
      <ul class="navbar-nav">
        <li class="nav-item mx-2"><a class="nav-link active" href="#">Home</a></li>
        <li class="nav-item mx-2"><a class="nav-link" href="about.php">About</a></li>
        <li class="nav-item mx-2"><a class="nav-link" href="#" data-bs-toggle="modal" data-bs-target="#loginModal">Login</a></li>
        <li class="nav-item mx-2"><a class="nav-link" href="#" data-bs-toggle="modal" data-bs-target="#registerModal">Register</a></li>
      </ul>
    </div>
  </div>
</nav>

<!-- HERO -->
<section class="hero">
  <div class="container">
    <h1>Welcome to ApartmentHub</h1>
    <p>Find your perfect apartment with ease. Connecting tenants and property managers in one smart platform.</p>
    <a href="#" class="btn btn-primary btn-lg mt-3" data-bs-toggle="modal" data-bs-target="#registerModal">Get Started</a>
  </div>
</section>

<!-- APARTMENTS -->
<section class="container mt-5">
  <h2 class="mb-4 text-primary">Available Apartments</h2>
  <div id="message-area"></div>
  <div class="row">
    <?php if ($apartments): ?>
      <?php foreach ($apartments as $apt): ?>
        <div class="col-md-4 mb-4">
          <div class="card apartment-card h-100 shadow-sm">
            <?php if ($apt['Image']): ?>
              <img src="<?= htmlspecialchars($apt['Image']) ?>" class="card-img-top" alt="<?= htmlspecialchars($apt['Name']) ?>">
            <?php endif; ?>
            <div class="card-body d-flex flex-column">
              <h5 class="card-title"><?= htmlspecialchars($apt['Name']) ?></h5>
              <p class="card-text"><?= htmlspecialchars($apt['Description']) ?></p>
              <p class="card-text"><strong>Monthly Rate:</strong> $<?= number_format($apt['MonthlyRate'],2) ?></p>

              <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'tenant'): ?>
                <!-- Logged-in tenant: AJAX Apply -->
                <button class="btn btn-success btn-sm mt-auto apply-btn" data-apartment="<?= $apt['ApartmentID']; ?>">Apply Now</button>
              <?php else: ?>
                <!-- Not logged-in: trigger login modal -->
                <button class="btn btn-success btn-sm mt-auto" data-bs-toggle="modal" data-bs-target="#loginModal">Apply Now</button>
              <?php endif; ?>

            </div>
          </div>
        </div>
      <?php endforeach; ?>
    <?php else: ?>
      <p class="text-muted">No apartments are currently available. Please check back later.</p>
    <?php endif; ?>
  </div>
</section>

<?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'tenant'): ?>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function(){
    $('.apply-btn').click(function(){
        var btn = $(this);
        var apartmentID = btn.data('apartment');

        $.ajax({
            url: 'apply_ajax.php',
            method: 'POST',
            data: { apartment_id: apartmentID },
            success: function(response){
                $('#message-area').html('<div class="alert alert-info">'+response+'</div>');
                btn.text('Applied');
            },
            error: function(){
                $('#message-area').html('<div class="alert alert-danger">Something went wrong. Try again.</div>');
            }
        });
    });
});
</script>
<?php endif; ?>


<!-- LOGIN MODAL -->
<div class="modal fade" id="loginModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Login</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <form action="actions/login.php" method="POST">
          <div class="mb-3">
            <label class="form-label">Username or Email</label>
            <input type="text" class="form-control" name="username" required>
          </div>

          <div class="mb-3">
            <label class="form-label">Password</label>
            <input type="password" class="form-control" name="password" required>
          </div>

          <button type="submit" class="btn btn-primary w-100">Login</button>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- REGISTER MODAL -->
<div class="modal fade" id="registerModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content position-relative">
      <div class="modal-header">
        <h5 class="modal-title">Tenant Registration</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <form id="registerForm" method="POST">
          <input type="hidden" name="action" value="register">
          <div class="row g-2">
            <div class="col-md-6">
              <label>First Name</label>
              <input type="text" class="form-control" name="firstname" required>
            </div>
            <div class="col-md-6">
              <label>Last Name</label>
              <input type="text" class="form-control" name="lastname" required>
            </div>
          </div>

          <div class="mt-3">
            <label>Username</label>
            <input type="text" class="form-control" name="username" required>
          </div>

          <div class="mt-3">
            <label>Email Address</label>
            <input type="email" class="form-control" name="email" required>
          </div>

          <div class="mt-3">
            <label>Phone Number</label>
            <input type="text" class="form-control" name="phone" required>
          </div>

          <div class="row g-2 mt-3">
            <div class="col-md-6">
              <label>Password</label>
              <input type="password" class="form-control" name="password" required>
            </div>
            <div class="col-md-6">
              <label>Confirm Password</label>
              <input type="password" class="form-control" name="confirm_password" required>
            </div>
          </div>

          <button type="submit" class="btn btn-primary w-100 mt-3">Register</button>
        </form>
        <!-- OTP Overlay INSIDE registration modal -->
        <div id="otpOverlay">
          <div class="otp-backdrop"></div>
          <div class="otp-center-box">
            <form id="otpForm" autocomplete="off">
              <div class="modal-content p-3">
                <div class="modal-header">
                  <h5 class="modal-title">Enter OTP</h5>
                </div>
                <div class="modal-body">
                  <input type="text" name="otp" class="form-control mb-2" placeholder="Enter OTP" required>
                  <input type="hidden" name="action" value="verify_otp">
                </div>
                <div class="modal-footer">
                  <button type="submit" class="btn btn-primary w-100">Verify OTP</button>
                </div>
              </div>
            </form>
          </div>
        </div>
        <!-- END OTP Overlay -->
      </div>
    </div>
  </div>
</div>

<footer>
  <p class="mb-0">&copy; 2025 ApartmentHub. All rights reserved.</p>
</footer>

<!-- JS Libraries -->
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$(function() {
  // Registration form submit via AJAX
  $('#registerForm').on('submit', function(e) {
    e.preventDefault();
    $.ajax({
      url: 'actions/register.php',
      type: 'POST',
      data: $(this).serialize(),
      success: function(response) {
        if (response.trim() === 'OTP_SENT') {
          $('#otpOverlay').fadeIn(200);
        } else {
          Swal.fire('Error', response, 'error');
        }
      }
    });
  });

  // OTP form submit via AJAX
  $('#otpForm').on('submit', function(e) {
    e.preventDefault();
    $.ajax({
      url: 'actions/register.php',
      type: 'POST',
      data: $(this).serialize(),
      success: function(response) {
        if (response.trim() === 'OTP_VALID') {
          $('#otpOverlay').fadeOut(200, function() {
            Swal.fire({
              icon: 'success',
              title: 'Registration complete!',
              showConfirmButton: false,
              timer: 1500
            }).then(() => {
              window.location.href = 'index.php';
            });
          });
        } else {
          Swal.fire('Invalid OTP', 'Please try again.', 'error');
        }
      }
    });
  });
});
</script>
</body>
</html> 