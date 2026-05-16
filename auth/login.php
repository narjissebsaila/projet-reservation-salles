<?php

/*
    Page : login.php

    Rôle :
    Permettre à un utilisateur
    de se connecter au système.
*/

session_start();

require_once "../config/database.php";


/*
    Vérifier si le formulaire
    a été envoyé.
*/
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    /*
        Récupération des données.
    */
    $email = $_POST["email"];
    $mot_de_passe = $_POST["mot_de_passe"];


    /*
        Rechercher l'utilisateur
        dans la base de données.
    */
    $sql = "SELECT * FROM utilisateurs WHERE email = ?";

    $stmt = $pdo->prepare($sql);

    $stmt->execute([$email]);

    $utilisateur = $stmt->fetch(PDO::FETCH_ASSOC);


    /*
        Vérifier si l'utilisateur existe
        et si le mot de passe est correct.
    */
    if (
        $utilisateur &&
        password_verify($mot_de_passe, $utilisateur["mot_de_passe"])
    ) {

        /*
            Créer la session utilisateur.
        */
        $_SESSION["id"] = $utilisateur["id"];

        $_SESSION["nom"] = $utilisateur["nom"];

        $_SESSION["role"] = $utilisateur["role"];


        /*
            Redirection selon le rôle.
        */
        if ($utilisateur["role"] == "admin") {

            header("Location: ../pages/index.php");
            exit;
        }

        /*
            Client simple.
        */
        else {

            header("Location: ../pages/index.php");
            exit;
        }
    }

    /*
        Erreur de connexion.
    */
    $erreur = "Email ou mot de passe incorrect.";
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Connexion - RoomBook</title>

    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

<div class="container">

    <div class="card">

        <h1>Connexion</h1>

        <?php if (isset($erreur)): ?>

            <div class="alert alert-error">
                <?= $erreur ?>
            </div>

        <?php endif; ?>

        <form method="POST" class="form">

            <label>Email</label>

            <input
                type="email"
                name="email"
                required
            >

            <label>Mot de passe</label>

            <input
                type="password"
                name="mot_de_passe"
                required
            >

            <button type="submit" class="btn-submit">
                Se connecter
            </button>

        </form>

    </div>

</div>

</body>
</html>