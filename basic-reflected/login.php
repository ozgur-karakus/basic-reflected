<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Giriş Yap</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #121212; color: white; }
        .card { background-color: #1f1f1f; border-radius: 15px; padding: 20px; }
        .btn { background-color: #6200ee; border: none; }
        .btn:hover { background-color: #3700b3; }
        .register-link { color: #aaa; cursor: pointer; text-decoration: underline; }
    </style>
</head>
<body>
    <div class="container d-flex justify-content-center align-items-center" style="height: 100vh;">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h3 class="card-title text-center">Giriş Yap</h3>
                    <form method="post">
                        <div class="mb-3">
                            <label for="username" class="form-label">Kullanıcı Adı</label>
                            <input type="text" class="form-control" id="username" name="username" required>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Parola</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">Giriş Yap</button>
                        </div>
                    </form>
                    <div class="d-flex justify-content-between mt-3">
                        <a href="nologin.php" class="register-link">Admin Giriş Yap</a>
                        <a href="register.php" class="register-link">Kayıt Ol</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php
    session_start();
    include 'db.php';

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $username = htmlspecialchars($_POST['username']);
        $password = $_POST['password'];

        try {
            $stmt = $pdo->prepare("SELECT id, password FROM users WHERE username = :username");
            $stmt->bindParam(':username', $username);
            $stmt->execute();
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['loggedin'] = true;
                $_SESSION['userid'] = $user['id'];
                $_SESSION['username'] = $username;
                header("Location: main.php");
                exit;
            } else {
                echo "<div class='alert alert-danger' role='alert'>Yanlış kullanıcı adı veya parola.</div>";
            }
        } catch (PDOException $e) {
            echo "<div class='alert alert-danger' role='alert'>Hata: " . $e->getMessage() . "</div>";
        }
    }
    ?>
</body>
</html>
