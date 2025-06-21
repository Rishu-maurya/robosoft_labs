<?php
$host = "localhost";
$dbname = "robosoft";
$user = "root";
$pass = "";

$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$username = trim($_POST['username']);
$gmail = trim($_POST['gmail']);
$password = password_hash(trim($_POST['password']), PASSWORD_DEFAULT);
$phone = trim($_POST['phone']);
$name = trim($_POST['name']);
$token = bin2hex(random_bytes(16));

$stmt = $conn->prepare("INSERT INTO users (username, gmail, password, phone, name, verification_token) VALUES (?, ?, ?, ?, ?, ?)");
$stmt->bind_param("ssssss", $username, $gmail, $password, $phone, $name, $token);

if ($stmt->execute()) {
    $verify_link = "http://localhost/phpmyadmin/index.php?route=/sql&pos=0&db=robosoft&table=users" . $token;
    $to = $gmail;
    $subject = "Verify your email";
    $message = "Hi $username,\n\nPlease click the link below to verify your email:\n$verify_link";
    $headers = "From: krishab394@gmail.com\r\n";

    if (mail($to, $subject, $message, $headers)) {
        echo "Registration successful! Please check your Gmail to verify.";
    } else {
        echo "Registration successful, but failed to send verification email.";
    }
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
