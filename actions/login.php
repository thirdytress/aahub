<?php
session_start();
require_once "../classes/database.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $db = new Database();

    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    // fetch tenant record from the correct table
    $tenant = $db->getTenantByUsername($username);

    // hard-coded admin credentials
    $adminUsername = 'admin1';
    $adminPassword = 'admin123';

    if ($tenant && password_verify($password, $tenant['password'])) {
        // regular tenant login
        $_SESSION['user_id'] = $tenant['tenant_id'];

        if ($tenant['username'] === $adminUsername) {
            // special admin logic
            $_SESSION['role'] = 'admin';
            header("Location: ../admin/dashboard.php");
        } else {
            $_SESSION['role'] = 'tenant';
            header("Location: ../tenant/dashboard.php");
        }
        exit();
    } elseif ($username === $adminUsername && $password === $adminPassword) {
        // fallback: allow hard-coded admin login even if not in DB
        $_SESSION['user_id'] = 0; // no real tenant_id
        $_SESSION['role'] = 'admin';
        header("Location: ../admin/dashboard.php");
        exit();
    } else {
        $error = "Invalid username or password";
        // redirect back to login page with error, or display inline
        $_SESSION['login_error'] = $error;
        header("Location: ../index.php");
        exit();
    }
} else {
    header("Location: ../index.php");
    exit();
}
