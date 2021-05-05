<?php require_once "inc/header.inc.php"; ?>
<?php

//restriction d'accès à la page profil SI on N'EST PAS connecté :
if( !userConnect() ){

	header('location:connexion.php'); //redirection vers la page de profil
	exit;
}
//---------------------------------------------------------------
if( adminConnect() ){ //SI c'est un admin qui est connecté, alors on affiche un titre 'administrateur'

	$content .= "<h3 style='color:tomato'>ADMINISTRATEUR</h3>";
}

//---------------------------------------------------------------
//debug( $_SESSION );

$pseudo = $_SESSION['membre']['pseudo'];

$content .= "<h3>Vos informations personnelles</h3>";

$content .= "<p>Votre prénom : ". $_SESSION['membre']['prenom'] ."</p>";

$content .= "<p>Votre nom : ". $_SESSION['membre']['nom'] ."</p>";
$content .= "<p>Votre email : ". $_SESSION['membre']['email'] ."</p>";
$content .= "<p>Votre adresse : ". $_SESSION['membre']['adresse'] ." ". $_SESSION['membre']['cp'] ." à " . $_SESSION['membre']['ville'] ."</p>";

//-------------------------------------------------------------------------------------------------
?>
<h1>PAGE PROFIL</h1>

<h2>Bonjour <?= $pseudo ?></h2>

<?= $content; ?>

<?php require_once "inc/footer.inc.php"; ?>
