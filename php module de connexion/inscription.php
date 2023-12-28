<?php
// SQL
$sql_serveur = "localhost";
$sql_utilisateur = "root";
$sql_motDePasse = "";
$sql_baseDeDonnees = "moduleconnexion";

$sql_connexion = new mysqli($sql_serveur, $sql_utilisateur, $sql_motDePasse, $sql_baseDeDonnees);

$error = false;
$validation = false;
// inscription
if (isset($_POST['inscription'])) {
    // Variables
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $login = $_POST['login'];
    $password = $_POST['password'];
    // cookies
    setcookie('nom', $nom);
    setcookie('prenom', $prenom);
    setcookie('login', $login);
    setcookie('password', $password);
    // messsages d'erreurs
    $sql = "SELECT * FROM utilisateurs WHERE login='" . $_POST['login'] . "'";
    $sql_resultat = $sql_connexion->query($sql);
    if ($_POST['password'] !== $_POST['confirm_password']) {
        $error = true;
        $error_message = "<span class='error'>Votre mot de passe n'est pas identique !</span>";
    } elseif (strlen($_POST['password']) < 7) {
        $error = true;
        $error_message = "<span class='error'>Votre mot de passe doit contenir au moins 7 caractères</span>";
    } elseif (mysqli_num_rows($sql_resultat)) {
        $error = true;
        $error_message = "<span class='error'>Cet identifiant est déjà utilisé !</span>";
    } else {
        // SQL 
        $sql = "INSERT INTO utilisateurs (nom, prenom, login, password)
        VALUE ('$nom', '$prenom', '$login', '$password')";
        // Exécution
        $sql_resultat = $sql_connexion->query($sql);
        $validation = true;
        $validation_message = "<span class='validation'>Félicitation pour
        votre inscription</span>";

    }
}

if (isset($_COOKIE['connexion'])) {
    header("Location: ./index.php");
}
if (empty($_COOKIE['nom'])) {
    $_COOKIE['nom'] = "";
}
if (empty($_COOKIE['prenom'])) {
    $_COOKIE['prenom'] = "";
}
if (empty($_COOKIE['login'])) {
    $_COOKIE['login'] = "";
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Inscription</title>
    <meta name="description" content="Page d'inscription du projet module de connexion">
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
        <h1>Inscription</h1>
        <?php
        // Messages d'erreure
        if ($error == true) {
            echo $error_message;
        }
        // Affichage du tableau
        if ($validation == false) {
            echo "<form method='post' action='inscription.php'>
    <input type='text' name='nom' value='" . $_COOKIE['nom'] . "' placeholder='Votre nom' required>
    <input type='text' name='prenom' value='" . $_COOKIE['prenom'] . "' placeholder='Votre prénom' required>
    <input type='text' name='login' value='" . $_COOKIE['login'] . "' placeholder='Votre identifiant' required>
    <input type='password' name='password' placeholder='Votre mot de passe' required>
    <input type='password' name='confirm_password' placeholder='Confirmation de votre mot de passe' required>
    <button type='submit' name='inscription'>Inscription</button>
</form>";
        }
        // Message de validation
        elseif ($validation == true) {
            echo $validation_message;
            echo "<a href='connexion.php'>se connecter</a>";
        }
        ?>
    </section>
</body>