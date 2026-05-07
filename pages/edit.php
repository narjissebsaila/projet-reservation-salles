<?php

/*
    Page : edit.php
    Rôle : afficher le formulaire de modification d'une réservation.
    Cette page récupère d'abord la réservation choisie grâce à son id.
*/
// Connexion à la base de données
require_once "../config/database.php";

/*
    On vérifie si l'id existe dans l'URL.
    Exemple :
    edit.php?id=2
*/
if (!isset($_GET["id"])) {
    header("Location: index.php");
    exit;
}
// Récupération de l'id
$id = $_GET["id"];

/*
    On récupère les informations de la réservation à modifier.
*/
$sql = "SELECT * FROM reservations WHERE id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$id]);
$reservation = $stmt->fetch(PDO::FETCH_ASSOC);

/*
    Si aucune réservation n'est trouvée, on retourne vers la liste.
*/
if (!$reservation) {
    header("Location: index.php");
    exit;
}

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
            <p>Mettre à jour les informations de la salle</p>
        </div>

        <a href="index.php" class="btn btn-primary">Retour</a>
    </header>

    <section class="card">
     
        //formulaire qui envoie vers update.php
    
        <form action="../actions/update.php" method="POST" class="form">

            <!-- Champ caché : il sert à envoyer l'id vers update.php -->
            <input type="hidden" name="id" value="<?= htmlspecialchars($reservation['id']); ?>">

            <label>Salle</label>
            <input 
                type="text" 
                name="salle" 
                value="<?= htmlspecialchars($reservation['salle']); ?>" 
                required
            >

            <label>Date de réservation</label>
            <input 
                type="date" 
                name="date_reservation" 
                value="<?= htmlspecialchars($reservation['date_reservation']); ?>" 
                required
            >

            <label>Heure de début</label>
            <input 
                type="time" 
                name="heure_debut" 
                value="<?= htmlspecialchars($reservation['heure_debut']); ?>" 
                required
            >

            <label>Heure de fin</label>
            <input 
                type="time" 
                name="heure_fin" 
                value="<?= htmlspecialchars($reservation['heure_fin']); ?>" 
                required
            >

            <label>Responsable</label>
            <input 
                type="text" 
                name="responsable" 
                value="<?= htmlspecialchars($reservation['responsable']); ?>" 
                required
            >

            <label>Motif</label>
            <textarea name="motif" required><?= htmlspecialchars($reservation['motif']); ?></textarea>

            <label>Statut</label>
            <select name="statut" required>
                <option value="en_attente" <?= $reservation['statut'] == 'en_attente' ? 'selected' : ''; ?>>
                    En attente
                </option>

                <option value="confirmee" <?= $reservation['statut'] == 'confirmee' ? 'selected' : ''; ?>>
                    Confirmée
                </option>

                <option value="annulee" <?= $reservation['statut'] == 'annulee' ? 'selected' : ''; ?>>
                    Annulée
                </option>
            </select>

            <button type="submit" class="btn-submit">Enregistrer les modifications</button>

        </form>

    </section>

</div>

</body>
</html>