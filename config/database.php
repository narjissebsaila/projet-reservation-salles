<?php

/*
    Fichier : database.php
    Rôle : établir la connexion entre PHP et la base de données MySQL.
    On utilise PDO car c'est une méthode sécurisée et recommandée.
*/

$host = "localhost";        // Serveur local
$dbname = "roombook";       // Nom de la base de données
$username = "root";         // Nom d'utilisateur MySQL
$password = "1234";             // Mot de passe MySQL, vide par défaut avec XAMPP

try {
    // Création de la connexion avec PDO
    $pdo = new PDO(
        "mysql:host=$host;dbname=$dbname;charset=utf8mb4",
        $username,
        $password
    );

    // Afficher les erreurs SQL clairement pendant le développement
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch (PDOException $e) {
    // Si la connexion échoue, on arrête le programme et on affiche l'erreur
    die("Erreur de connexion à la base de données : " . $e->getMessage());
}