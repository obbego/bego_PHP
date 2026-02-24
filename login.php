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

    $stmt = $con->prepare("SELECT id, psw FROM utenti WHERE user = ?");
    $stmt->execute([$user]);

    if ($stmt->rowCount() === 1) {

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($pass === $row["psw"]) {

            $_SESSION["user_id"] = $row["id"];
            $_SESSION["username"] = $user;

            header("Location: homepage.php");
            exit;
        } else {
            $msg = "Password errata";
        }

    } else {
        $msg = "Utente inesistente";
    }
}
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-dark d-flex align-items-center vh-100">

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-4">

            <div class="card shadow">
                <div class="card-body">

                    <h3 class="text-center mb-4">Login</h3>

                    <form method="POST">
                        <input name="username" class="form-control mb-3" placeholder="Username" required>
                        <input type="password" name="password" class="form-control mb-3" placeholder="Password" required>
                        <button class="btn btn-primary w-100">Login</button>
                    </form>

                    <a href="register.php" class="btn btn-secondary w-100 mt-2">Registrati</a>

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
