<?php
$host = "localhost";
$dbname = "robosoft";
$user = "root";
$pass = "";

$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_GET['token'])) {
    $token = $_GET['token'];

    $stmt = $conn->prepare("SELECT * FROM users WHERE verification_token = ?");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $update = $conn->prepare("UPDATE users SET is_verified = 1, verification_token = NULL WHERE verification_token = ?");
        $update->bind_param("s", $token);
        $update->execute();
        echo "Email verified successfully!";
    } else {
        echo "Invalid or expired token.";
    }

    $stmt->close();
} else {
    echo "No verification token provided.";
}

$conn->close();
?>
