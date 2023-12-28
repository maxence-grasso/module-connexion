<?php
// SQL
$sql_serveur = "localhost";
$sql_utilisateur = "root";
$sql_motDePasse = "";
$sql_baseDeDonnees = "moduleconnexion";

$sql_connexion = new mysqli($sql_serveur, $sql_utilisateur, $sql_motDePasse, $sql_baseDeDonnees);

if (isset($_COOKIE['connexion'])) {
    header("Location: ./profil.php");
}
if (empty($_COOKIE['login'])) {
    $_COOKIE['login'] = "";
}
if (empty($_COOKIE['password'])) {
    $_COOKIE['password'] = "";
}
$error = false;
// Connexion
if (isset($_POST['connexion'])) {
    $sql = "SELECT * FROM utilisateurs";
    $sql_resultat = $sql_connexion->query($sql);
    foreach ($sql_resultat as $utilisateur) {
        if ($utilisateur['login'] == $_POST['login'] and $utilisateur['password'] == $_POST['password']) {
            $id = $utilisateur['id'];
            $nom = $utilisateur['nom'];
            $prenom = $utilisateur['prenom'];
            $login = $utilisateur['login'];
            $password = $utilisateur['password'];
            setcookie('connexion', true);
            setcookie('id', $id);
            setcookie('nom', $nom);
            setcookie('prenom', $prenom);
            setcookie('login', $login);
            setcookie('password', $password);
            header("Location: ./profil.php");
        }
        // Si mauvais mot de passe
        else {
            $error = true;
            $error_message = "<span class='error'>Identifiant ou mot de passe incorrect</span>";
        }
    }
}
?>

<!--head-->
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Connexion</title>
    <meta name="description" content="Page de connexion du projet module de connexion">
    <meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1.0">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <!--Header-->
    <header>
        <nav>
            <a href='index.php'>Retourner à l'accueil</a>
        </nav>
        <?php
        if (isset($_COOKIE['connexion'])) {
            echo "<p>Bienvenue " . $_COOKIE['prenom'] . " " . $_COOKIE['nom'] . "</p>";
        }
        ?>
    </header>
    <section class="connexion">
        <h1>Connexion</h1>
        <?php
        if ($error == true) {
            echo $error_message;
        }
        ?>
        <form method='post' action='connexion.php'>
            <input type='text' name='login' value='<?= $_COOKIE['login'] ?>' placeholder='Votre identifiant'>
            <input type='password' name='password' value='<?= $_COOKIE['password'] ?>' placeholder='Votre mot de passe'>
            <button type='submit' name='connexion'>Connexion</button>
        </form>
        <p>    Vous n'avez pas encore de compte ?
        <p>
            <?php
            if (isset($_COOKIE['connexion'])) {
                echo "<a href='profil.php'>Votre profil</a>";
            } else {
                echo "<a href='inscription.php'>S'inscrire</a>";
            }
            ?>
    </section>
</body>