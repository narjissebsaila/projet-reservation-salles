<?php

/*
    Page : index.php
    Rôle : afficher la liste des réservations.
    C'est la partie READ du CRUD.
*/

require_once "../config/database.php";

// Récupération de toutes les réservations depuis la base de données
$sql = "SELECT * FROM reservations ORDER BY date_reservation, heure_debut";
$stmt = $pdo->query($sql);
$reservations = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>RoomBook - Réservations</title>

    <!-- Lien vers le fichier CSS -->
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

<div class="container">

    <!-- En-tête de l'application -->
    <header class="header">
        <div>
            <h1>RoomBook</h1>
            <p>Gestion des réservations de salles</p>
        </div>

        <a href="create.php" class="btn btn-primary">+ Nouvelle réservation</a>
    </header>

    <!-- Carte principale -->
    <section class="card">

        <div class="card-header">
            <h2>Liste des réservations</h2>

            <!-- Champ de recherche simple côté navigateur -->
            <input 
                type="text" 
                id="searchInput" 
                placeholder="Rechercher une salle, un responsable..."
                class="search-input"
            >
        </div>

        <!-- Tableau des réservations -->
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Salle</th>
                    <th>Date</th>
                    <th>Début</th>
                    <th>Fin</th>
                    <th>Responsable</th>
                    <th>Motif</th>
                    <th>Statut</th>
                    <th>Actions</th>
                </tr>
            </thead>

            <tbody id="reservationTable">
                <?php foreach ($reservations as $reservation): ?>
                    <tr>
                        <td><?= htmlspecialchars($reservation['id']); ?></td>
                        <td><?= htmlspecialchars($reservation['salle']); ?></td>
                        <td><?= htmlspecialchars($reservation['date_reservation']); ?></td>
                        <td><?= htmlspecialchars($reservation['heure_debut']); ?></td>
                        <td><?= htmlspecialchars($reservation['heure_fin']); ?></td>
                        <td><?= htmlspecialchars($reservation['responsable']); ?></td>
                        <td><?= htmlspecialchars($reservation['motif']); ?></td>

                        <td>
                            <span class="badge <?= htmlspecialchars($reservation['statut']); ?>">
                                <?= htmlspecialchars($reservation['statut']); ?>
                            </span>
                        </td>

                        <td class="actions">
                            <a href="edit.php?id=<?= $reservation['id']; ?>" class="btn btn-edit">
                                Modifier
                            </a>

                            <a 
                                href="../actions/delete.php?id=<?= $reservation['id']; ?>" 
                                class="btn btn-delete"
                                onclick="return confirm('Voulez-vous vraiment supprimer cette réservation ?');"
                            >
                                Supprimer
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

    </section>

</div>

<script src="../assets/js/script.js"></script>

</body>
</html>