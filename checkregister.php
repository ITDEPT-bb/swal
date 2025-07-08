<?php
require("config.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    if (empty($name) || empty($email) || empty($password)) {
        echo json_encode(['status' => 'error', 'message' => 'All fields are required.']);
        exit;
    }

    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

    $stmt = $conn->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
    $stmt->bind_param('sss', $name, $email, $hashedPassword);

    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Registration successful. You can now log in.']);
        session_start();
        $user_id = $stmt->insert_id;
        $_SESSION['user_id'] = $user_id;
    } else {
        echo json_encode(['status' => 'error', 'message' => 'There was an error during registration.']);
    }

    $stmt->close();
    $conn->close();
}
?>