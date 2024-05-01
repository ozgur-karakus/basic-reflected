<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kullanıcı Paneli</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #121212; color: white; }
        .card { background-color: #1f1f1f; border-radius: 15px; padding: 20px; }
        .btn { background-color: #6200ee; border: none; }
        .btn:hover { background-color: #3700b3; }
        .nav-link { color: white; text-decoration: none; }
        .nav-link:hover { text-decoration: underline; }
    </style>
</head>
<body>
    <?php
    session_start();
    include 'db.php';

    if (!isset($_SESSION['loggedin'])) {
        header("Location: login.php");
        exit;
    }

    $username = htmlspecialchars($_SESSION['username']);
    $userid = $_SESSION['userid'];

   
    if (isset($_GET['delete'])) {
        $note_id = $_GET['delete'];
        try {
            $stmt = $pdo->prepare("DELETE FROM user_notes WHERE id = :id AND user_id = :userid"); 
            $stmt->bindParam(':id', $note_id);
            $stmt->bindParam(':userid', $userid);
            $stmt->execute();
            header("Location: main.php?message=deleted");
            exit;
        } catch (PDOException $e) {
            $message = "Hata: Not silinemedi.";
        }
    }

   
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $note = htmlspecialchars($_POST['note']);
        try {
            $stmt = $pdo->prepare("INSERT INTO user_notes (user_id, note) VALUES (:userid, :note)");
            $stmt->bindParam(':userid', $userid);
            $stmt->bindParam(':note', $note);
            $stmt->execute();
            header("Location: main.php?message=success");
            exit;
        } catch (PDOException $e) {
            $message = "Hata: Not kaydedilemedi.";
        }
    }

    
    $stmt = $pdo->prepare("SELECT * FROM user_notes WHERE user_id = :userid");
    $stmt->bindParam(':userid', $userid);
    $stmt->execute();
    $notes = $stmt->fetchAll(PDO::FETCH_ASSOC);
    ?>

    <div class="container" style="margin-top: 20px;">
        <div class="d-flex justify-content-between align-items-center">
            <h3>Merhaba, <?php echo $username; ?>!</h3>
            <div>
                <a href="login.php" class="nav-link">Çıkış Yap</a>
                <a href="nomain.php" class="nav-link">Güvensiz Not'a Git</a>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-body">
                <h5 class="card-title">Kendine Not Yaz</h5>
                <form method="post">
                    <div class="mb-3">
                        <label for="note" class="form-label">Not</label>
                        <textarea class="form-control" id="note" name="note" rows="4" placeholder="Notunuzu buraya yazın..."></textarea>
                    </div>
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary">Kaydet</button>
                    </div>
                </form>

                <?php if (isset($_GET['message']) && $_GET['message'] == 'success') { ?>
                    <div class='alert alert-info mt-3'>Not kaydedildi!</div>
                <?php } elseif (isset($_GET['message']) && $_GET['message'] == 'deleted') { ?>
                    <div class='alert alert-info mt-3'>Not silindi!</div>
                <?php } ?>

                <hr>

                <h5>Notlarınız:</h5>
                <?php foreach ($notes as $note) { ?>
                    <div class="card mt-2">
                        <div class="card-body d-flex justify-content-between">
                            <p><?php echo htmlspecialchars($note['note']); ?></p>
                            <a href="?delete=<?php echo $note['id']; ?>" class="btn btn-danger">Sil</a>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
</body>
</html>
