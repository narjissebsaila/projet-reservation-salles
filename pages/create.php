<?php
/*
    Page : create.php
    Rôle : afficher le formulaire d'ajout d'une réservation.
*/
// On récupère l'erreur envoyée dans l'URL (ex: ?error=heure)
// Si aucune erreur → $error = null
$error = $_GET["error"] ?? null;
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ajouter une réservation - RoomBook</title>
    <link rel="stylesheet" href="../assets/css/style.css?v=<?= time(); ?>">
</head>
<body>

<div class="container">

    <header class="header">
        <div>
            <h1>Nouvelle réservation</h1>
            <p>Remplir les informations de la salle</p>
        </div>

        <a href="index.php" class="btn btn-primary">Retour</a>
    </header>

    <section class="card">
        <?php if ($error): ?>

    <?php if ($error == "heure"): ?>
        <div class="alert alert-error">
            L'heure de début doit être inférieure à l'heure de fin.
        </div>
    <?php endif; ?>

    <?php if ($error == "conflit"): ?>
        <div class="alert alert-error">
            Cette salle est déjà réservée dans ce créneau.
        </div>
    <?php endif; ?>

    <?php if ($error == "statut"): ?>
        <div class="alert alert-error">
            Le statut choisi est invalide.
        </div>
    <?php endif; ?>
    <?php if ($error == "vide"): ?>
    <div class="alert alert-error">
        Tous les champs sont obligatoires.
    </div>
<?php endif; ?>

<?php endif; ?>
        <form action="../actions/store.php" method="POST" class="form">

            <label>Salle</label>
            <input type="text" name="salle" required placeholder="Ex : Salle A">

            <label>Date de réservation</label>
            <input type="date" name="date_reservation" required>

            <label>Heure de début</label>
            <input type="time" name="heure_debut" required>

            <label>Heure de fin</label>
            <input type="time" name="heure_fin" required>

            <label>Responsable</label>
            <input type="text" name="responsable" required placeholder="Ex : Mme MARFOQ">

            <label>Motif</label>
            <textarea name="motif" required placeholder="Ex : Cours, réunion, TP..."></textarea>

            <label>Statut</label>
            <select name="statut" required>
                <option value="en_attente">En attente</option>
                <option value="confirmee">Confirmée</option>
                <option value="annulee">Annulée</option>
            </select>

            <button type="submit" class="btn-submit">Enregistrer</button>

        </form>

    </section>

</div>

</body>
</html>