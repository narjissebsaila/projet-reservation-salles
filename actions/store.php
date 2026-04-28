<?php

/*
    Fichier : store.php
    Rôle : recevoir les données du formulaire et les enregistrer dans la base.
    Cette page représente la partie CREATE du CRUD.
*/

require_once "../config/database.php";

// Vérifier si le formulaire a été envoyé avec la méthode POST
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // Récupération des données envoyées par le formulaire
    $salle = $_POST["salle"];
    $date_reservation = $_POST["date_reservation"];
    $heure_debut = $_POST["heure_debut"];
    $heure_fin = $_POST["heure_fin"];
    $responsable = $_POST["responsable"];
    $motif = $_POST["motif"];
    $statut = $_POST["statut"];

    /*
        Validation du statut :
        On accepte seulement les statuts présents dans la base de données.
    */
    $statutsAutorises = ["en_attente", "confirmee", "annulee"];

    if (!in_array($statut, $statutsAutorises)) {
        header("Location: ../pages/create.php?error=statut");
        exit;
    }

    /*
        Validation de l'heure :
        L'heure de début doit être avant l'heure de fin.
    */
    if ($heure_debut >= $heure_fin) {
        header("Location: ../pages/create.php?error=heure");
        exit;
    }

    /*
        Vérification du conflit :
        On cherche si la même salle est déjà réservée
        à la même date et dans un créneau qui se chevauche.
    */
    $sqlConflit = "
        SELECT * FROM reservations
        WHERE salle = ?
        AND date_reservation = ?
        AND statut != 'annulee'
        AND heure_debut < ?
        AND heure_fin > ?
    ";

    $stmtConflit = $pdo->prepare($sqlConflit);

    $stmtConflit->execute([
        $salle,
        $date_reservation,
        $heure_fin,
        $heure_debut
    ]);

    $reservationExiste = $stmtConflit->fetch();

    /*
        Si une réservation existe déjà,
        et que la nouvelle réservation n'est pas annulée,
        alors on bloque l'ajout.
    */
    if ($reservationExiste && $statut != "annulee") {
        header("Location: ../pages/create.php?error=conflit");
        exit;
    }

    /*une erreur si champ vide */
    if (empty($salle) || empty($date_reservation) || empty($heure_debut) || empty($heure_fin)) {
    header("Location: ../pages/create.php?error=vide");
    exit;
}

    /*
        Insertion dans la base de données.
        On utilise prepare() pour éviter les injections SQL.
    */
    $sql = "
        INSERT INTO reservations 
        (salle, date_reservation, heure_debut, heure_fin, responsable, motif, statut)
        VALUES (?, ?, ?, ?, ?, ?, ?)
    ";

    $stmt = $pdo->prepare($sql);

    $stmt->execute([
        $salle,
        $date_reservation,
        $heure_debut,
        $heure_fin,
        $responsable,
        $motif,
        $statut
    ]);

    // Après l'ajout, retourner vers la liste
    header("Location: ../pages/index.php");
    exit;
}

// Si quelqu'un ouvre store.php directement sans formulaire,
// on le redirige vers la page d'ajout.
header("Location: ../pages/index.php?success=ajout");
exit;