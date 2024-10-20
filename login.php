<?php
session_start();
$host = 'localhost';
$db = 'php_mysqli_blog';
$user = 'root';
$pass = 'mysql';
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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>

<body class="bg-light">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h3 class="text-center mb-4">Accedi</h3>

                        <?php if (!empty($loginError)): ?>
                            <div class="alert alert-danger">
                                <?= htmlspecialchars($loginError) ?>
                            </div>
                        <?php endif; ?>

                        <form method="post" action="">
                            <div class="form-group">
                                <label for="username">Username:</label>
                                <input type="text" id="username" name="username" class="form-control" required>
                            </div>

                            <div class="form-group py-2 ">
                                <label for="password">Password:</label>
                                <input type="password" id="password" name="password" class="form-control" required>
                            </div>

                            <button type="submit" class="btn btn-primary btn-block">Accedi</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>
</body>

</html>