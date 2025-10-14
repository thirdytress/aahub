<?php
session_start();

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../classes/database.php';

// Registration and OTP send
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['action']) && $_POST['action'] === 'register') {
    $firstname = trim($_POST['firstname']);
    $lastname = trim($_POST['lastname']);
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);

    $db = new Database();
    $result = $db->registerTenant($firstname, $lastname, $username, $email, $phone, $password, $confirm_password);

    if ($result === true) {
        // Generate OTP
        $otp = rand(100000, 999999);
        $_SESSION['otp'] = $otp;
        $_SESSION['email'] = $email;

        // Send OTP Email
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'martynjosephseloterio@gmail.com'; // Your Gmail
            $mail->Password = 'ezkieflbviwffyqr'; // Your Gmail App Password
            $mail->SMTPSecure = 'ssl';
            $mail->Port = 465;

            $mail->setFrom('martynjosephseloterio@gmail.com', 'Your System Name');
            $mail->addAddress($email, "$firstname $lastname");

            $mail->isHTML(true);
            $mail->Subject = 'Your OTP Code';
            $mail->Body = "
                <h3>Hello $firstname!</h3>
                <p>Your OTP code is: <b>$otp</b></p>
                <p>Please enter this OTP to complete your registration.</p>
            ";

            $mail->send();
            echo 'OTP_SENT';
            exit;
        } catch (Exception $e) {
            echo "OTP could not be sent. Mailer Error: {$mail->ErrorInfo}";
            exit;
        }
    } else {
        echo "Error: $result";
        exit;
    }
}

// OTP verification
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['action']) && $_POST['action'] === 'verify_otp') {
    $user_otp = trim($_POST['otp']);
    if (isset($_SESSION['otp']) && $user_otp == $_SESSION['otp']) {
        unset($_SESSION['otp']);
        echo 'OTP_VALID';
    } else {
        echo 'OTP_INVALID';
    }
    exit;
}
?>