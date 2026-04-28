<?php

/*
    Fichier : delete.php
    Rôle : supprimer une réservation de la base de données.
    Cette page représente la partie DELETE du CRUD.
*/

require_once "../config/database.php";

/*
    On vérifie si l'identifiant existe dans l'URL.
    Exemple d'URL :
    delete.php?id=3
*/
if (isset($_GET["id"])) {

    // On récupère l'id de la réservation
    $id = $_GET["id"];

    /*
        Requête SQL sécurisée avec prepare().
        Le ? sera remplacé par la valeur de $id.
    */
    $sql = "DELETE FROM reservations WHERE id = ?";

    $stmt = $pdo->prepare($sql);

    // Exécution de la suppression
    $stmt->execute([$id]);

    // Après suppression, on retourne vers la liste
    header("Location: ../pages/index.php");
    exit;

} else {
    // Si aucun id n'est envoyé, on retourne vers la liste
    header("Location: ../pages/index.php");
    exit;
}