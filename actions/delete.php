<?php

/*
    Fichier : delete.php

    Rôle :
    Supprimer une réservation existante.

    Cette page représente la partie DELETE du CRUD.
*/

require_once "../config/database.php";


/*
    Vérifier si l'id existe dans l'URL.
    Exemple : delete.php?id=3
*/
if (isset($_GET["id"])) {

    /*
        Récupérer l'id de la réservation.
    */
    $id = $_GET["id"];


    /*
        Préparer la requête de suppression.
    */
    $sql = "DELETE FROM reservations WHERE id = ?";

    $stmt = $pdo->prepare($sql);


    /*
        Exécuter la suppression.
    */
    $stmt->execute([$id]);


    /*
        Retourner vers la liste après suppression.
    */
    header("Location: ../pages/index.php?success=suppression");
    exit;
}


/*
    Si aucun id n'est envoyé,
    on retourne vers la liste.
*/
header("Location: ../pages/index.php");
exit;