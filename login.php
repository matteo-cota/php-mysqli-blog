<?php
session_start();
$host = 'localhost';
$db = 'php_mysqli_blog';
$user = 'root';
$pass = '';
$dsn = "mysql:host=$host;dbname=$db;charset=utf8";

try {
    $pdo = new PDO($dsn, $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $username = $_POST['username'];
        $password = $_POST['password'];


        $stmt = $pdo->prepare('SELECT * FROM users WHERE username = :username AND password = MD5(:password)');
        $stmt->execute(['username' => $username, 'password' => $password]);

        $user = $stmt->fetch();
        if ($user) {
            $_SESSION['loggedin'] = true;
            header('Location: posts.php');
            exit;
        } else {
            echo 'Login fallito! Username o password errati.';
        }
    }
} catch (PDOException $e) {
    echo 'Errore di connessione: ' . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8">
    <title>Login</title>
</head>

<body>
    <h1>Login</h1>
    <form method="post" action="">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required><br>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required><br>

        <input type="submit" value="Accedi">
    </form>
</body>

</html>