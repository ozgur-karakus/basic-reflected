<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Üye Ol</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #121212; color: white; }
        .card { background-color: #1f1f1f; border-radius: 15px; padding: 20px; }
        .btn { background-color: #6200ee; border: none; }
        .btn:hover { background-color: #3700b3; }
        .login-link { color: #aaa; cursor: pointer; text-decoration: underline; }
    </style>
</head>
<body>
    <div class="container d-flex justify-content-center align-items-center" style="height: 100vh;">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h3 class="card-title text-center">Üye Ol</h3>
                    <form method="post">
                        <div class="mb-3">
                            <label for="username" class="form-label">Kullanıcı Adı</label>
                            <input type="text" class="form-control" id="username" name="username" required>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Parola</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">E-Posta</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">Üye Ol</button>
                        </div>
                    </form>
                    <div class="d-flex justify-content-between mt-3">
                        <a href="login.php" class="login-link">Giriş Yap</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php
    include 'db.php';

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $username = htmlspecialchars($_POST['username']);
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $email = htmlspecialchars($_POST['email']);

        try {
            $stmt = $pdo->prepare("INSERT INTO users (username, password, email) VALUES (:username, :password, :email)");
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':password', $password);
            $stmt->bindParam(':email', $email);
            $stmt->execute();
            echo "<div class='alert alert-success' role='alert'>Başarıyla kaydoldunuz! Giriş yapabilirsiniz.</div>";
        } catch (PDOException $e) {
            echo "<div class='alert alert-danger' role='alert'>Hata: " . $e->getMessage() . "</div>";
        }
    }
    ?>
</body>
</html>
