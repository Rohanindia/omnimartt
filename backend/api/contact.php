<?php
include("../config/db.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name    = $conn->real_escape_string(trim($_POST['name'] ?? ''));
    $email   = $conn->real_escape_string(trim($_POST['email'] ?? ''));
    $subject = $conn->real_escape_string(trim($_POST['subject'] ?? 'Contact Form'));
    $message = $conn->real_escape_string(trim($_POST['message'] ?? ''));

    if (empty($name) || empty($email) || empty($message)) {
        header("Location: ../../contact.php?error=missing_fields");
        exit;
    }

    // Create table if it doesn't exist (safety net)
    $conn->query("CREATE TABLE IF NOT EXISTS contact_messages (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(100),
        email VARCHAR(100),
        subject VARCHAR(200),
        message TEXT,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");

    $result = $conn->query(
        "INSERT INTO contact_messages (name, email, subject, message) 
         VALUES ('$name', '$email', '$subject', '$message')"
    );

    if ($result) {
        header("Location: ../../contact.php?sent=1");
    } else {
        header("Location: ../../contact.php?error=db_error");
    }
    exit;
}

header("Location: ../../contact.php");
exit;
?>
