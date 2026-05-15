<?php

/*
    Page : create.php

    Rôle :
    Afficher le formulaire d'ajout d'une réservation.

    Nouvelle version :
    La salle n'est plus écrite manuellement.
    Elle vient maintenant de la table salles.
*/

require_once "../config/database.php";


/*
    Récupérer toutes les salles
    depuis la base de données.
*/
$sql = "SELECT * FROM salles ORDER BY nom";
$stmt = $pdo->query($sql);
$salles = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ajouter une réservation - RoomBook</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

<div class="container">

    <header class="header">
        <div>
            <h1>Nouvelle réservation</h1>
            <p>Remplir les informations de la réservation</p>
        </div>

        <a href="index.php" class="btn btn-primary">Retour</a>
    </header>


    <section class="card">

        <?php if (isset($_GET["error"])): ?>

            <?php if ($_GET["error"] == "heure"): ?>
                <div class="alert alert-error">
                    L'heure de début doit être inférieure à l'heure de fin.
                </div>
            <?php endif; ?>

            <?php if ($_GET["error"] == "conflit"): ?>
                <div class="alert alert-error">
                    Cette salle est déjà réservée dans ce créneau.
                </div>
            <?php endif; ?>

            <?php if ($_GET["error"] == "statut"): ?>
                <div class="alert alert-error">
                    Le statut choisi est invalide.
                </div>
            <?php endif; ?>

            <?php if ($_GET["error"] == "vide"): ?>
                <div class="alert alert-error">
                    Tous les champs obligatoires doivent être remplis.
                </div>
            <?php endif; ?>

        <?php endif; ?>


        <form action="../actions/store.php" method="POST" class="form">

            <label>Salle</label>

            <!--
                On envoie salle_id et non plus salle.
            -->
            <select name="salle_id" required>
                <option value="">-- Choisir une salle --</option>

                <?php foreach ($salles as $salle): ?>
                    <option value="<?= $salle["id"] ?>">
                        <?= htmlspecialchars($salle["nom"]) ?>
                        -
                        <?= htmlspecialchars($salle["localisation"]) ?>
                    </option>
                <?php endforeach; ?>
            </select>


            <label>Date de réservation</label>
            <input type="date" name="date_reservation" required>


            <label>Heure de début</label>
            <input type="time" name="heure_debut" required>


            <label>Heure de fin</label>
            <input type="time" name="heure_fin" required>


            <label>Responsable</label>
            <input type="text" name="responsable" required>


            <label>Motif</label>
            <textarea name="motif"></textarea>


            <label>Statut</label>
            <select name="statut" required>
                <option value="en_attente">En attente</option>
                <option value="confirmee">Confirmée</option>
                <option value="refusee">Refusée</option>
                <option value="annulee">Annulée</option>
            </select>


            <button type="submit" class="btn-submit">
                Enregistrer
            </button>

        </form>

    </section>

</div>

</body>
</html>