<?php

/*
    Page : index.php

    Rôle :
    Afficher la liste des réservations.

    Nouvelle version :
    La salle vient maintenant de la table salles.
*/

require_once "../config/database.php";


/*
    Récupérer les réservations avec le nom de la salle.
*/
$sql = "
    SELECT 
        r.id,
        r.date_reservation,
        r.heure_debut,
        r.heure_fin,
        r.responsable,
        r.motif,
        r.statut,
        s.nom AS nom_salle
    FROM reservations r
    INNER JOIN salles s ON r.salle_id = s.id
    ORDER BY r.date_reservation DESC, r.heure_debut DESC
";

$stmt = $pdo->query($sql);
$reservations = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>RoomBook - Réservations</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

<div class="container">

    <header class="header">
        <div>
            <h1>RoomBook</h1>
            <p>Gestion des réservations de salles</p>
        </div>

        <a href="create.php" class="btn btn-primary">
            + Nouvelle réservation
        </a>
    </header>

    <section class="card">

        <h2>Liste des réservations</h2>

        <?php if (isset($_GET["success"]) && $_GET["success"] == "ajout"): ?>
            <div class="alert alert-success">
                Réservation ajoutée avec succès.
            </div>
        <?php endif; ?>


        <?php if (isset($_GET["success"]) && $_GET["success"] == "modification"): ?>
    <div class="alert alert-success">
        Réservation modifiée avec succès.
    </div>
   <?php endif; ?>

  <?php if (isset($_GET["success"]) && $_GET["success"] == "suppression"): ?>
    <div class="alert alert-success">
        Réservation supprimée avec succès.
    </div>
  <?php endif; ?>


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

            <tbody>

            <?php foreach ($reservations as $reservation): ?>
                <tr>
                    <td><?= $reservation["id"] ?></td>

                    <td><?= htmlspecialchars($reservation["nom_salle"]) ?></td>

                    <td><?= htmlspecialchars($reservation["date_reservation"]) ?></td>

                    <td><?= htmlspecialchars($reservation["heure_debut"]) ?></td>

                    <td><?= htmlspecialchars($reservation["heure_fin"]) ?></td>

                    <td><?= htmlspecialchars($reservation["responsable"]) ?></td>

                    <td><?= htmlspecialchars($reservation["motif"]) ?></td>

                    <td>
                           <span class="badge <?= $reservation["statut"] ?>">
                           <?= htmlspecialchars($reservation["statut"]) ?>
                          </span>
                    </td>

                    <td>
                      <div class="actions">
                        <a href="edit.php?id=<?= $reservation["id"] ?>" class="btn btn-edit"> Modifier </a>

                        <a href="../actions/delete.php?id=<?= $reservation["id"] ?>"
                             class="btn btn-delete" onclick="return confirm('Voulez-vous vraiment supprimer cette réservation ?')">Supprimer
                        </a>
                      </div>
                    </td>
                </tr>
            <?php endforeach; ?>

            </tbody>
        </table>

    </section>

</div>


</body>
</html>