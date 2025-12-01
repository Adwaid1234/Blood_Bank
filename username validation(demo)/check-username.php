<?php
header('Content-Type: application/json');

// Database connection
$host = 'localhost';
$dbname = 'user_system';
$user = 'your_db_username';
$pass = 'your_db_password';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if (isset($_GET['username'])) {
        $username = trim($_GET['username']);
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $exists = $stmt->fetchColumn() > 0;

        echo json_encode(['exists' => $exists]);
    } else {
        echo json_encode(['error' => 'Username not provided']);
    }

} catch (PDOException $e) {
    echo json_encode(['error' => 'Database error']);
}
?>
