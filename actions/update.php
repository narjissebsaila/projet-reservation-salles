<?php

/*
    Fichier : update.php
    Rôle : modifier une réservation existante.
*/

require_once "../config/database.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $id = $_POST["id"];
    $salle_id = $_POST["salle_id"];
    $date_reservation = $_POST["date_reservation"];
    $heure_debut = $_POST["heure_debut"];
    $heure_fin = $_POST["heure_fin"];
    $responsable = $_POST["responsable"];
    $motif = $_POST["motif"];
    $statut = $_POST["statut"];

    if ($heure_debut >= $heure_fin) {
        header("Location: ../pages/edit.php?id=$id&error=heure");
        exit;
    }

    /*
        Vérifier s'il existe une autre réservation
        dans la même salle et le même créneau.
    */
    $sqlConflit = "
        SELECT *
        FROM reservations
        WHERE id != ?
        AND salle_id = ?
        AND date_reservation = ?
        AND statut != 'annulee'
        AND heure_debut < ?
        AND heure_fin > ?
    ";

    $stmtConflit = $pdo->prepare($sqlConflit);

    $stmtConflit->execute([
        $id,
        $salle_id,
        $date_reservation,
        $heure_fin,
        $heure_debut
    ]);

    $reservationExiste = $stmtConflit->fetch();

    if ($reservationExiste && $statut != "annulee") {
        header("Location: ../pages/edit.php?id=$id&error=conflit");
        exit;
    }

    /*
        Modifier la réservation.
    */
    $sql = "
        UPDATE reservations
        SET salle_id = ?,
            date_reservation = ?,
            heure_debut = ?,
            heure_fin = ?,
            responsable = ?,
            motif = ?,
            statut = ?
        WHERE id = ?
    ";

    $stmt = $pdo->prepare($sql);

    $stmt->execute([
        $salle_id,
        $date_reservation,
        $heure_debut,
        $heure_fin,
        $responsable,
        $motif,
        $statut,
        $id
    ]);

    header("Location: ../pages/index.php?success=modification");
    exit;
}

header("Location: ../pages/index.php");
exit;