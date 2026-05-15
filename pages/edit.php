<?php

/*
    Page : edit.php
    Rôle : afficher le formulaire de modification.
*/

require_once "../config/database.php";

$id = $_GET["id"];

$sql = "SELECT * FROM reservations WHERE id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$id]);
$reservation = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$reservation) {
    header("Location: index.php");
    exit;
}

/*
    Récupérer les salles pour les afficher dans la liste.
*/
$sqlSalles = "SELECT * FROM salles ORDER BY nom";
$stmtSalles = $pdo->query($sqlSalles);
$salles = $stmtSalles->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier une réservation - RoomBook</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

<div class="container">

    <header class="header">
        <div>
            <h1>Modifier la réservation</h1>
            <p>Mettre à jour les informations</p>
        </div>

        <a href="index.php" class="btn btn-primary">Retour</a>
    </header>

    <section class="card">

        <form action="../actions/update.php" method="POST" class="form">

            <input type="hidden" name="id" value="<?= $reservation["id"] ?>">

            <label>Salle</label>
            <select name="salle_id" required>
                <?php foreach ($salles as $salle): ?>
                    <option value="<?= $salle["id"] ?>"
                        <?php if ($salle["id"] == $reservation["salle_id"]) echo "selected"; ?>>
                        <?= htmlspecialchars($salle["nom"]) ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <label>Date de réservation</label>
            <input type="date" name="date_reservation"
                   value="<?= $reservation["date_reservation"] ?>" required>

            <label>Heure de début</label>
            <input type="time" name="heure_debut"
                   value="<?= $reservation["heure_debut"] ?>" required>

            <label>Heure de fin</label>
            <input type="time" name="heure_fin"
                   value="<?= $reservation["heure_fin"] ?>" required>

            <label>Responsable</label>
            <input type="text" name="responsable"
                   value="<?= htmlspecialchars($reservation["responsable"]) ?>" required>

            <label>Motif</label>
            <textarea name="motif"><?= htmlspecialchars($reservation["motif"]) ?></textarea>

            <label>Statut</label>
            <select name="statut" required>
                <option value="en_attente" <?= $reservation["statut"] == "en_attente" ? "selected" : "" ?>>
                    En attente
                </option>

                <option value="confirmee" <?= $reservation["statut"] == "confirmee" ? "selected" : "" ?>>
                    Confirmée
                </option>

                <option value="refusee" <?= $reservation["statut"] == "refusee" ? "selected" : "" ?>>
                    Refusée
                </option>

                <option value="annulee" <?= $reservation["statut"] == "annulee" ? "selected" : "" ?>>
                    Annulée
                </option>
            </select>

            <button type="submit" class="btn-submit">
                Enregistrer les modifications
            </button>

        </form>

    </section>

</div>

</body>
</html>