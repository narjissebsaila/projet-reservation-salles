<?php

/*
    Fichier : store.php

    Rôle :
    Recevoir les données du formulaire d'ajout
    puis enregistrer une réservation dans la base de données.

    Cette page représente la partie CREATE du CRUD.
*/

require_once "../config/database.php";


/*
    Vérifier si le formulaire a été envoyé
    avec la méthode POST.
*/
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    /*
        Récupération des données envoyées
        depuis le formulaire.
    */
    $salle_id = $_POST["salle_id"];
    $date_reservation = $_POST["date_reservation"];
    $heure_debut = $_POST["heure_debut"];
    $heure_fin = $_POST["heure_fin"];
    $responsable = $_POST["responsable"];
    $motif = $_POST["motif"];
    $statut = $_POST["statut"];


    /*
        Vérification des champs obligatoires.

        Si un champ important est vide,
        on retourne vers le formulaire.
    */
    if (
        empty($salle_id) ||
        empty($date_reservation) ||
        empty($heure_debut) ||
        empty($heure_fin) ||
        empty($responsable) ||
        empty($statut)
    ) {

        header("Location: ../pages/create.php?error=vide");
        exit;
    }


    /*
        Validation du statut.

        On accepte uniquement les valeurs
        présentes dans la base de données.
    */
    $statutsAutorises = [
        "en_attente",
        "confirmee",
        "refusee",
        "annulee"
    ];

    /*
        Si le statut n'est pas valide,
        on bloque l'insertion.
    */
    if (!in_array($statut, $statutsAutorises)) {

        header("Location: ../pages/create.php?error=statut");
        exit;
    }


    /*
        Vérification des heures.

        L'heure de début doit être
        inférieure à l'heure de fin.
    */
    if ($heure_debut >= $heure_fin) {

        header("Location: ../pages/create.php?error=heure");
        exit;
    }


    /*
        Vérification des conflits.

        On recherche une réservation :
        - dans la même salle
        - à la même date
        - avec un chevauchement d'heures
        - et qui n'est pas annulée
    */
    $sqlConflit = "
        SELECT *
        FROM reservations
        WHERE salle_id = ?
        AND date_reservation = ?
        AND statut != 'annulee'
        AND heure_debut < ?
        AND heure_fin > ?
    ";

    /*
        Préparation de la requête SQL.
    */
    $stmtConflit = $pdo->prepare($sqlConflit);

    /*
        Exécution de la requête.
    */
    $stmtConflit->execute([
        $salle_id,
        $date_reservation,
        $heure_fin,
        $heure_debut
    ]);

    /*
        Récupération du résultat.
    */
    $reservationExiste = $stmtConflit->fetch();


    /*
        Si une réservation existe déjà,
        alors on empêche l'ajout.
    */
    if ($reservationExiste && $statut != "annulee") {

        header("Location: ../pages/create.php?error=conflit");
        exit;
    }

    /*
        Requête d'insertion.

        On ajoute la réservation
        dans la base de données.
    */
    $sql = "
        INSERT INTO reservations
        (
            salle_id,
            date_reservation,
            heure_debut,
            heure_fin,
            responsable,
            motif,
            statut
        )
        VALUES (?, ?, ?, ?, ?, ?, ?)
    ";

    /*
        Préparation de la requête SQL.
    */
    $stmt = $pdo->prepare($sql);

    /*
        Exécution de la requête
        avec les données du formulaire.
    */
    $stmt->execute([
        $salle_id,
        $date_reservation,
        $heure_debut,
        $heure_fin,
        $responsable,
        $motif,
        $statut
    ]);

    /*
        Après l'ajout,
        retour vers la liste.
    */
    header("Location: ../pages/index.php?success=ajout");
    exit;
}

/*
    Si quelqu'un ouvre store.php directement
    sans formulaire,
    on le redirige vers la liste.
*/
header("Location: ../pages/index.php");
exit;