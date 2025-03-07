<?php
require 'connection.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password_hash'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        header("Location: index.php");
        exit;
    } else {
        echo "Ongeldige inloggegevens!";
    }
}
?>

<form method="POST">
    <input type="email" name="email" placeholder="E-mail" required>
    <input type="password" name="password" placeholder="Wachtwoord" required>
    <button type="submit">Login</button>
</form>
