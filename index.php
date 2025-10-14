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
  <style>
    body {
      background: linear-gradient(to right, #e3f2fd, #ffffff);
      font-family: 'Poppins', sans-serif;
    }
    .navbar {
      box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }
    .hero {
      text-align: center;
      padding: 100px 20px;
    }
    .hero h1 {
      font-weight: 700;
      color: #0d6efd;
    }
    .hero p {
      font-size: 1.1rem;
      color: #333;
    }
    .apartment-card img {
      height: 200px;
      object-fit: cover;
    }
    .modal-header {
      background: #0d6efd;
      color: #fff;
    }
    .form-control:focus {
      box-shadow: none;
      border-color: #0d6efd;
    }
    footer {
      background-color: #f8f9fa;
      padding: 20px;
      text-align: center;
      margin-top: 80px;
      border-top: 1px solid #ddd;
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
      background: rgba(0,0,0,0.2);
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
      box-shadow: 0 0 24px 4px #0d6efd33;
      border: 2px solid #0d6efd;
    }
  </style>
</head>
<body>

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
              <a href="apply_apartment.php?apartment_id=<?= $apt['ApartmentID']; ?>" class="btn btn-success btn-sm">Apply Now</a>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    <?php else: ?>
      <p class="text-muted">No apartments are currently available. Please check back later.</p>
    <?php endif; ?>
  </div>
</section>

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
          <label class="form-label">Select Role</label>
          <select class="form-select mb-3" name="role" required>
            <option value="tenant" selected>Tenant</option>
            <option value="admin">Admin</option>
          </select>

          <div class="mb-3">
            <label>Username or Email</label>
            <input type="text" class="form-control" name="username" required>
          </div>

          <div class="mb-3">
            <label>Password</label>
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