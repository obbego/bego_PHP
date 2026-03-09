<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

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
$msgType = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $marca = trim($_POST["marca"] ?? "");
    $marca=htmlspecialchars($marca, ENT_QUOTES);
    $modello = trim($_POST["modello"] ?? "");
    $modello=htmlspecialchars($modello, ENT_QUOTES);
    $cilindrata = filter_var($_POST["cilindrata"], FILTER_SANITIZE_NUMBER_INT);
    $potenza = filter_var($_POST["potenza"], FILTER_SANITIZE_NUMBER_INT);
    $lunghezza = filter_var($_POST["lunghezza"], FILTER_SANITIZE_NUMBER_INT);
    $larghezza = filter_var($_POST["larghezza"], FILTER_SANITIZE_NUMBER_INT);

    if (!$marca || !$modello || !$cilindrata || !$potenza || !$lunghezza || !$larghezza) {
        header("Location: homepage.php?err=1");
        exit;
    }

    $stmt = $con->prepare("
        INSERT INTO auto
        (marca, modello, cilindrata, potenza, lunghezza, larghezza, proprietario)
        VALUES (?,?,?,?,?,?,?)
    ");

    $stmt->execute([
            $marca,
            $modello,
            (int)$cilindrata,
            (int)$potenza,
            (int)$lunghezza,
            (int)$larghezza,
            $_SESSION["user_id"]
    ]);

    header("Location: homepage.php?ok=1");
    exit;
}

if (isset($_GET["ok"])) {
    $msg = "Auto aggiunta con successo";
    $msgType = "success";
}

if (isset($_GET["err"])) {
    $msg = "Compila tutti i campi";
    $msgType = "danger";
}


$stmt = $con->prepare("SELECT * FROM auto WHERE proprietario = ?");
$stmt->execute([$_SESSION["user_id"]]);
$auto = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>MACCCHINE-BRUM-BRUM</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-dark text-light">

<div class="container py-5">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <h2 class="m-0">MACCCHINE BRUM BRUM</h2>

        <a href="login.php" class="btn btn-outline-light border">
            Logout (<?= htmlspecialchars($_SESSION["username"]) ?>)
        </a>

    </div>


    <?php if ($msg): ?>
        <div class="alert alert-<?= $msgType ?> text-center">
        <?= htmlspecialchars($msg) ?>
        </div>
    <?php endif; ?>

    <div class="card">
        <div class="card-body text-dark">

            <h4>Aggiungi Auto</h4>

            <form method="POST" class="row g-3">

                <div class="col-md-4">
                    <input name="marca" class="form-control" placeholder="Marca">
                </div>

                <div class="col-md-4">
                    <input name="modello" class="form-control" placeholder="Modello">
                </div>

                <div class="col-md-4">
                    <input name="cilindrata" type="number" class="form-control" placeholder="Cilindrata (cc)" min = 0>
                </div>

                <div class="col-md-4">
                    <input name="potenza" type="number" class="form-control" placeholder="Potenza (cv)" min = 0>
                </div>

                <div class="col-md-4">
                    <input name="lunghezza" type="number" class="form-control" placeholder="Lunghezza (cm)" min = 0>
                </div>

                <div class="col-md-4">
                    <input name="larghezza" type="number" class="form-control" placeholder="Larghezza (cm)" min = 0>
                </div>

                <div class="col-12">
                    <button class="btn btn-success w-100">Salva Auto</button>
                </div>

            </form>

        </div>
    </div>

    <div class="card mt-4 mb-5">
        <div class="card-body text-dark">

            <h4>Garage</h4>

            <?php if ($auto): ?>

                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th>Marca</th>
                        <th>Modello</th>
                        <th>CC</th>
                        <th>CV</th>
                        <th>Lung.</th>
                        <th>Larg.</th>
                    </tr>
                    </thead>
                    <tbody>

                    <?php foreach ($auto as $a): ?>
                        <tr>
                            <td><?= htmlspecialchars($a["marca"]) ?></td>
                            <td><?= htmlspecialchars($a["modello"]) ?></td>
                            <td><?= htmlspecialchars($a["cilindrata"]) ?></td>
                            <td><?= htmlspecialchars($a["potenza"]) ?></td>
                            <td><?= htmlspecialchars($a["lunghezza"]) ?></td>
                            <td><?= htmlspecialchars($a["larghezza"]) ?></td>
                        </tr>
                    <?php endforeach; ?>

                    </tbody>
                </table>

            <?php else: ?>
                <p>Nessuna auto registrata.</p>
            <?php endif; ?>

        </div>
    </div>

</div>

</body>
</html>
