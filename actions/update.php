<?php

/*
    Fichier : update.php
    Rôle : enregistrer les modifications d'une réservation.
    Cette page représente la partie UPDATE du CRUD.
*/

require_once "../config/database.php";

/*
    On vérifie que le formulaire a été envoyé avec POST.
*/
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // Récupération des données envoyées par le formulaire
    $id = $_POST["id"];
    $salle = $_POST["salle"];
    $date_reservation = $_POST["date_reservation"];
    $heure_debut = $_POST["heure_debut"];
    $heure_fin = $_POST["heure_fin"];
    $responsable = $_POST["responsable"];
    $motif = $_POST["motif"];
    $statut = $_POST["statut"];

    /*
        Validation simple :
        l'heure de début doit être avant l'heure de fin.
    */
    if ($heure_debut >= $heure_fin) {
        die("Erreur : l'heure de début doit être inférieure à l'heure de fin.");
    }

    /*
        Vérification du conflit d'horaires.
        Important :
        On exclut la réservation actuelle avec id != ?
        pour ne pas la comparer avec elle-même.
    */
    $sqlConflit = "
        SELECT * FROM reservations
        WHERE id != ?
        AND salle = ?
        AND date_reservation = ?
        AND statut != 'annulee'
        AND heure_debut < ?
        AND heure_fin > ?
    ";

    $stmtConflit = $pdo->prepare($sqlConflit);
    $stmtConflit->execute([
        $id,
        $salle,
        $date_reservation,
        $heure_fin,
        $heure_debut
    ]);

    $reservationExiste = $stmtConflit->fetch();

    /*
        Si on trouve une réservation dans le même créneau,
        on bloque la modification.
    */
    if ($reservationExiste && $statut != "annulee") {
        die("Erreur : cette salle est déjà réservée dans ce créneau.");
    }

    /*
        Mise à jour de la réservation dans la base.
        On utilise prepare() pour sécuriser la requête SQL.
    */
    $sql = "
        UPDATE reservations
        SET 
            salle = ?,
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
        $salle,
        $date_reservation,
        $heure_debut,
        $heure_fin,
        $responsable,
        $motif,
        $statut,
        $id
    ]);

    // Après modification, retour vers la liste
    header("Location: ../pages/index.php");
    exit;
}