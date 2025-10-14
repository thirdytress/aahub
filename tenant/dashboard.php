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
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .navbar-brand i {
            color: var(--accent-gold);
            filter: drop-shadow(0 0 10px rgba(212, 175, 55, 0.5));
            animation: pulse 2s infinite;
        }
        
        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.1); }
        }
        
        .btn-logout {
            background: linear-gradient(135deg, var(--accent-gold) 0%, var(--luxury-gold) 100%);
            border: 2px solid rgba(255,255,255,0.2);
            color: var(--deep-navy);
            padding: 10px 30px;
            border-radius: 30px;
            font-weight: 700;
            letter-spacing: 0.5px;
            transition: all 0.4s ease;
            box-shadow: 0 4px 20px rgba(212, 175, 55, 0.4);
            text-transform: uppercase;
            font-size: 0.85rem;
        }
        
        .btn-logout:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 30px rgba(212, 175, 55, 0.6);
            background: linear-gradient(135deg, var(--luxury-gold) 0%, var(--accent-gold) 100%);
        }
        
        .welcome {
            margin-top: 60px;
            margin-bottom: 70px;
            animation: fadeInDown 1s ease;
            position: relative;
            z-index: 1;
        }
        
        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translateY(-50px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .welcome h2 {
            font-size: 3.5rem;
            font-weight: 800;
            color: var(--primary-dark);
            margin-bottom: 20px;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.1);
            position: relative;
            display: inline-block;
        }
        
        .welcome h2::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
            width: 100px;
            height: 4px;
            background: linear-gradient(90deg, transparent, var(--accent-gold), transparent);
            border-radius: 2px;
        }
        
        .welcome p {
            font-size: 1.2rem;
            color: var(--earth-brown);
            font-weight: 500;
            margin-top: 25px;
        }
        
        .card {
            border: none;
            border-radius: 30px;
            transition: all 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            box-shadow: 
                0 20px 60px rgba(0,0,0,0.15),
                inset 0 1px 0 rgba(255,255,255,0.6);
            background: linear-gradient(145deg, #ffffff 0%, #f8f5f0 100%);
            overflow: hidden;
            position: relative;
            border: 2px solid rgba(212, 175, 55, 0.2);
        }
        
        .card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 6px;
            background: linear-gradient(90deg, 
                var(--primary-dark) 0%, 
                var(--primary-blue) 50%, 
                var(--accent-gold) 100%);
            transform: scaleX(0);
            transition: transform 0.5s ease;
        }
        
        .card:hover::before {
            transform: scaleX(1);
        }
        
        .card::after {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, 
                transparent, 
                rgba(212, 175, 55, 0.1), 
                transparent);
            transition: left 0.6s ease;
        }
        
        .card:hover::after {
            left: 100%;
        }
        
        .card:nth-child(1) { animation: fadeInUp 0.8s ease 0.1s both; }
        .card:nth-child(2) { animation: fadeInUp 0.8s ease 0.3s both; }
        .card:nth-child(3) { animation: fadeInUp 0.8s ease 0.5s both; }
        .card:nth-child(4) { animation: fadeInUp 0.8s ease 0.7s both; }
        
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(80px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .card:hover {
            transform: translateY(-20px) scale(1.03);
            box-shadow: 
                0 30px 80px rgba(0,0,0,0.25),
                0 0 0 1px rgba(212, 175, 55, 0.5),
                inset 0 1px 0 rgba(255,255,255,0.8);
        }
        
        .card-body {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            padding: 2.5rem 2rem;
            position: relative;
            z-index: 1;
        }
        
        .icon-container {
            width: 130px;
            height: 130px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 2rem;
            position: relative;
            transition: all 0.5s ease;
        }
        
        .icon-container::before {
            content: '';
            position: absolute;
            inset: -8px;
            border-radius: 50%;
            padding: 8px;
            background: linear-gradient(135deg, var(--accent-gold), var(--primary-blue), var(--accent-gold));
            -webkit-mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
            -webkit-mask-composite: xor;
            mask-composite: exclude;
            animation: rotate 4s linear infinite;
            opacity: 0;
            transition: opacity 0.5s ease;
        }
        
        .card:hover .icon-container::before {
            opacity: 1;
        }
        
        @keyframes rotate {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }
        
        .card:nth-child(1) .icon-container {
            background: linear-gradient(135deg, #3498db20 0%, #2c3e5020 100%);
            box-shadow: 0 10px 40px rgba(52, 152, 219, 0.3);
        }
        
        .card:nth-child(2) .icon-container {
            background: linear-gradient(135deg, #d4af3720 0%, #c9a96120 100%);
            box-shadow: 0 10px 40px rgba(212, 175, 55, 0.3);
        }
        
        .card:nth-child(3) .icon-container {
            background: linear-gradient(135deg, #8b735520 0%, #2c3e5020 100%);
            box-shadow: 0 10px 40px rgba(139, 115, 85, 0.3);
        }
        
        .card:nth-child(4) .icon-container {
            background: linear-gradient(135deg, #95a5a620 0%, #3498db20 100%);
            box-shadow: 0 10px 40px rgba(149, 165, 166, 0.3);
        }
        
        .card:hover .icon-container {
            transform: rotateY(360deg) scale(1.1);
        }
        
        .icon-container i {
            font-size: 4rem;
            transition: all 0.5s ease;
        }
        
        .card:nth-child(1) .icon-container i { 
            color: var(--primary-blue);
            filter: drop-shadow(0 5px 15px rgba(52, 152, 219, 0.4));
        }
        .card:nth-child(2) .icon-container i { 
            color: var(--accent-gold);
            filter: drop-shadow(0 5px 15px rgba(212, 175, 55, 0.4));
        }
        .card:nth-child(3) .icon-container i { 
            color: var(--earth-brown);
            filter: drop-shadow(0 5px 15px rgba(139, 115, 85, 0.4));
        }
        .card:nth-child(4) .icon-container i { 
            color: var(--soft-gray);
            filter: drop-shadow(0 5px 15px rgba(149, 165, 166, 0.4));
        }
        
        .card:hover .icon-container i {
            transform: scale(1.2);
        }
        
        .card-title {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
            color: var(--primary-dark);
            letter-spacing: 0.5px;
        }
        
        .card-text {
            color: var(--earth-brown);
            margin-bottom: 2rem;
            font-size: 1rem;
            line-height: 1.6;
            font-weight: 500;
        }
        
        .btn-card {
            border: none;
            border-radius: 25px;
            padding: 15px 50px;
            font-weight: 700;
            transition: all 0.4s ease;
            position: relative;
            overflow: hidden;
            color: white;
            letter-spacing: 1px;
            text-transform: uppercase;
            font-size: 0.9rem;
        }
        
        .card:nth-child(1) .btn-card {
            background: linear-gradient(135deg, var(--primary-blue) 0%, var(--primary-dark) 100%);
            box-shadow: 0 8px 25px rgba(52, 152, 219, 0.4);
        }
        
        .card:nth-child(2) .btn-card {
            background: linear-gradient(135deg, var(--accent-gold) 0%, var(--luxury-gold) 100%);
            box-shadow: 0 8px 25px rgba(212, 175, 55, 0.4);
        }
        
        .card:nth-child(3) .btn-card {
            background: linear-gradient(135deg, var(--earth-brown) 0%, var(--primary-dark) 100%);
            box-shadow: 0 8px 25px rgba(139, 115, 85, 0.4);
        }
        
        .card:nth-child(4) .btn-card {
            background: linear-gradient(135deg, var(--soft-gray) 0%, var(--primary-dark) 100%);
            box-shadow: 0 8px 25px rgba(149, 165, 166, 0.4);
        }
        
        .btn-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 40px rgba(0,0,0,0.4);
        }
        
        .btn-card::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            border-radius: 50%;
            background: rgba(255,255,255,0.4);
            transform: translate(-50%, -50%);
            transition: width 0.6s, height 0.6s;
        }
        
        .btn-card:hover::before {
            width: 400px;
            height: 400px;
        }
        
        .btn-card span {
            position: relative;
            z-index: 1;
        }
        
        .floating-decoration {
            position: fixed;
            pointer-events: none;
            z-index: 0;
        }
        
        .deco-1 {
            top: 10%;
            left: 5%;
            width: 100px;
            height: 100px;
            background: radial-gradient(circle, rgba(212, 175, 55, 0.1), transparent);
            border-radius: 50%;
            animation: float 6s ease-in-out infinite;
        }
        
        .deco-2 {
            bottom: 15%;
            right: 8%;
            width: 150px;
            height: 150px;
            background: radial-gradient(circle, rgba(52, 152, 219, 0.1), transparent);
            border-radius: 50%;
            animation: float 8s ease-in-out infinite reverse;
        }
        
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-30px); }
        }
        
        @media (max-width: 768px) {
            .welcome h2 {
                font-size: 2.5rem;
            }
            
            .card-body {
                padding: 2rem 1.5rem;
            }
            
            .icon-container {
                width: 110px;
                height: 110px;
            }
            
            .icon-container i {
                font-size: 3rem;
            }
        }
    </style>
</head>
<body>

<div class="floating-decoration deco-1"></div>
<div class="floating-decoration deco-2"></div>

<nav class="navbar navbar-expand-lg">
  <div class="container">
    <a class="navbar-brand" href="#">
        <i class="bi bi-building-fill"></i>
        <span>ApartmentHub</span>
    <a href="/ahub/logout.php" class="btn btn-danger">
    <i class="bi bi-box-arrow-right me-2"></i>Logout
</a>


    </div>
  </div>
</nav>

<div class="container text-center">
    <div class="welcome">
        <h2>Welcome Home</h2>
        <p>Your complete apartment management dashboard</p>
    </div>

    <div class="row justify-content-center g-4">
        <!-- Available Apartments -->
        <div class="col-md-6 col-lg-3">
            <div class="card h-100">
                <div class="card-body">
                    <div class="icon-container">
                        <i class="bi bi-buildings"></i>
                    </div>
                    <h5 class="card-title">Available Apartments</h5>
                    <p class="card-text">Discover your perfect living space from our curated selection</p>
                    <a href="view_apartments.php" class="btn-card w-100">
    <span>Browse Units</span>
</a>

                </div>
            </div>
        </div>

        <!-- My Applications -->
        <div class="col-md-6 col-lg-3">
            <div class="card h-100">
                <div class="card-body">
                    <div class="icon-container">
                        <i class="bi bi-file-earmark-text-fill"></i>
                    </div>
                    <h5 class="card-title">My Applications</h5>
                    <p class="card-text">Track your rental applications and approval status</p>
                    <a href="my_applications.php" class="btn-card w-100">
    <span>Browse Units</span>
</a>

                </div>
            </div>
        </div>

        <!-- My Leases -->
        <div class="col-md-6 col-lg-3">
            <div class="card h-100">
                <div class="card-body">
                    <div class="icon-container">
                        <i class="bi bi-key-fill"></i>
                    </div>
                    <h5 class="card-title">My Leases</h5>
                    <p class="card-text">Access your current lease agreements and documents</p>
                    <a href="my_leases.php" class="btn-card w-100">
    <span>Browse Units</span>
</a>

                </div>
            </div>
        </div>

        <!-- Update Profile -->
        <div class="col-md-6 col-lg-3">
            <div class="card h-100">
                <div class="card-body">
                    <div class="icon-container">
                        <i class="bi bi-person-fill-gear"></i>
                    </div>
                    <h5 class="card-title">Update Profile</h5>
                    <p class="card-text">Manage your personal information and preferences</p>
                    <a href="update_profile.php" class="btn-card w-100">
    <span>Browse Units</span>
</a>

                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
    function navigate(section) {
        alert(`Navigating to ${section} page.\n\nIn your PHP application, this would link to:\n- Apartments: view_apartments.php\n- Applications: my_applications.php\n- Leases: my_leases.php\n- Profile: update_profile_form.php`);
    }
</script>
</body>
</html>