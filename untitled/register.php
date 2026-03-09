<?php
session_start();

try {
    $con = new PDO(
            "mysql:host=192.168.60.144;dbname=francesco_bego_auto;",
            "francesco_bego",
            "accaduti.immaginosa.",
            [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
            ]
    );
} catch (PDOException $e) {
    die("Errore connessione: " . $e->getMessage());
}

$msg = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $user = trim($_POST["username"] ?? "");
    $pass = $_POST["password"] ?? "";

    if ($user && $pass) {

        $check = $con->prepare("SELECT id FROM utenti WHERE user=?");
        $check->execute([$user]);

        if ($check->rowCount()) {
            $msg = "Username già esistente";
        } else {

            $ins = $con->prepare("INSERT INTO utenti (user,psw) VALUES (?,?)");
            $ins->execute([$user,$pass]);

            header("Location: login.php");
            exit;
        }

    } else {
        $msg = "Compila tutti i campi";
    }
}
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Registrazione</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-dark d-flex align-items-center vh-100">

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-4">

            <div class="card shadow">
                <div class="card-body">

                    <h3 class="text-center mb-4">Registrazione</h3>

                    <form method="POST">
                        <input name="username" class="form-control mb-3" placeholder="Username" required>
                        <input type="password" name="password" class="form-control mb-3" placeholder="Password" required>
                        <button class="btn btn-success w-100">Crea account</button>
                    </form>

                    <a href="login.php" class="btn btn-secondary w-100 mt-2">Torna al login</a>

                    <?php if($msg): ?>
                        <div class="alert alert-danger mt-3 text-center">
                            <?= htmlspecialchars($msg) ?>
                        </div>
                    <?php endif; ?>

                </div>
            </div>

        </div>
    </div>
</div>

</body>
</html>