<head>
    <meta charset="UTF-8">
    <title>Mon profil</title>
    <meta name="description" content="Toutes les informations de mon profil">
    <meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1.0">
    <link rel="stylesheet" href="style.css">
</head>
<?php
if (empty($_COOKIE['connexion'])) {
    header("Location: ./connexion.php");
}
if (isset($_POST['deco'])) {
    setcookie('nom');
    setcookie('prenom');
    setcookie('login');
    setcookie('password');
    setcookie('connexion');
    header("Location: ./index.php");

}
$prenom = $_COOKIE['prenom'];
$nom = $_COOKIE['nom'];
$login = $_COOKIE['login'];
$error = false;
$validation = false;
// SQL
$sql_serveur = "localhost";
$sql_utilisateur = "root";
$sql_motDePasse = "";
$sql_baseDeDonnees = "moduleconnexion";

$sql_connexion = new mysqli($sql_serveur, $sql_utilisateur, $sql_motDePasse, $sql_baseDeDonnees);

$id = $_COOKIE['id'];
$sql = "SELECT * FROM utilisateurs";
$sql_resultat = $sql_connexion->query($sql);
// Modification du prénom
if (isset($_POST['update_prenom'])) {
    $prenom = $_POST['prenom'];
    setcookie('prenom', $prenom);
    $sql = "UPDATE utilisateurs SET prenom='$prenom' WHERE id='$id'";
    $sql_resultat = $sql_connexion->query($sql);
    $validation = true;
    $validation_message = "<span class='validation'>Votre prénom a bien été remplacé !</span>";
}
// Modification du nom
elseif (isset($_POST['update_nom'])) {
    $nom = $_POST['nom'];
    setcookie('nom', $nom);
    $sql = "UPDATE utilisateurs SET nom='$nom' WHERE id='$id'";
    $sql_resultat = $sql_connexion->query($sql);
    $validation = true;
    $validation_message = "<span class='validation'>Votre nom a bien été remplacé !</span>";
}
// Modification login
elseif (isset($_POST['update_login'])) {
    $login = $_POST['login'];
    setcookie('login', $login);
    $sql = "SELECT * FROM utilisateurs WHERE login='" . $_POST['login'] . "'";
    $sql_resultat = $sql_connexion->query($sql);
    if (mysqli_num_rows($sql_resultat)) {
        $error = true;
        $error_message = "<span class='error'>Cet identifiant est déjà utiliser.</span>";
    } else {
        $sql = "UPDATE utilisateurs SET login='$login' WHERE id='$id'";
        $sql_resultat = $sql_connexion->query($sql);
        $validation = true;
        $validation_message = "<span class='validation'>Votre identifiant a bien été modifié !.</span>";
    }

}
// Modification mdp
elseif (isset($_POST['update_password'])) {
    setcookie('password', $_POST['password']);
    $password = $_POST['password'];
    if ($_POST['password'] !== $_POST['confirm_password']) {
        $error = true;
        $error_message = "<span class='error'>Votre mot de passe n'est pas identique !</span>";
    } elseif (strlen($_POST['password']) < 7) {
        $error = true;
        $error_message = "<span class='error'>Votre mot de passe doit contenir au moins 7 caractères</span>";
    } else {
        $sql = "UPDATE utilisateurs SET password='$password' WHERE id='$id'";
        $sql_resultat = $sql_connexion->query($sql);
        $validation = true;
        $validation_message = "<span class='validation'>Votre mot de passe a bien été modifié.</span>";
    }
}

?>
<!--head-->
<!DOCTYPE html>
<html lang="fr">
<body>
    <!--header-->
    <header>
        <nav>
            <a href='index.php'>Retourner à l'accueil</a>
        </nav>
        <?php
        if (isset($_COOKIE['connexion'])) {
            if ($_COOKIE['id'] == 42) {
                echo "<a href='admin.php' class='btn_admin'>Page d'administration</a>";
            } else {
                echo "<p>Bienvenue " . $prenom . " " . $nom . "</p>";
            }
        }
        ?>
    </header>
    <section class="profil">
    <form method='post' action='profil.php'>
            <button class="deco" type='submit' name='deco'>Déconnexion</button>
        </form>
        <h3>Information de votre compte</h3>
        <?php
        // SQL //
        $sql = "SELECT prenom AS 'Prénom', nom AS 'Nom', login AS 'Identifiant', password AS 'Mot de passe'
    FROM utilisateurs WHERE id='" . $_COOKIE['id'] . "'";
        $sql_resultat = $sql_connexion->query($sql);

        echo "<table border='1' cellpadding='15' rules='rows'>";
        echo "<thead><tr>";
        while ($champ = $sql_resultat->fetch_field()) {
            echo "<th>{$champ->name}</th>";
        }
        echo "</tr></thead>";
        echo "<tbody>";
        while ($ligne = $sql_resultat->fetch_assoc()) {
            echo "<tr>";
            foreach ($ligne as $valeur) {
                echo "<td>$valeur</td>";
            }
            echo "</tr>";
        }
        echo "</tbody>";
        echo "</table>";
        if ($error == true) {
            echo $error_message;
        } elseif ($validation == true) {
            echo $validation_message;
        }
        ?>
        <div class="modif">
            <span>Modifier vos informations</span>
            <div class="modif_int">
                <form method='post' action='profil.php'>
                    <label for='prenom'>Votre prénom</label>
                    <input type='text' name='prenom' id='prenom' value='<?= $prenom ?>' placeholder='Prénom'
                        required>
                    <button type='submit' name='update_prenom'>Validé</button>
                </form>

                <form method='post' action='profil.php'>
                    <label for='nom'>Votre nom</label>
                    <input type='text' name='nom' id='nom' value='<?= $nom ?>' placeholder='Nom' required>
                    <button type='submit' name='update_nom'>Validé</button>
                </form>

                <form method='post' action='profil.php'>
                    <label for='login'>Votre identifiant</label>
                    <input type='text' name='login' id='login' value='<?= $login ?>'
                        placeholder='Identifiant' required>
                    <button type='submit' name='update_login'>Validé</button>
                </form>

                <form method='post' action='profil.php'>
                    <label for='mdp'>Mot de passe</label>
                    <div>
                        <input type='password' name='password' id='mdp' placeholder='Votre mot de passe' required>
                        <input type='password' name='confirm_password' placeholder='Confirmation de votre mot de passe'
                            required>
                    </div>
                    <button type='submit' name='update_password'>Validé</button>
                </form>
            </div>
        </div>
    </section>
</body>
