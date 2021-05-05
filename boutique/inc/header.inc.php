<?php require_once "init.inc.php"; //Inclusion du fichier init.inc.php dans le header ?>

<!DOCTYPE html>
<html lang='fr'>
<head>
	<meta charset="utf-8">
	<title>Site boutique</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<!-- CDN CSS BOOTSTRAP -->
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

    <!-- CDN FONT AWESOME-->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.2/css/all.css" integrity="sha384-oS3vJWv+0UjzBfQzYUhtDYW+Pj2yciDJxpsK1OYPAYjqT085Qq/1cq5FLXAZQ7Ay" crossorigin="anonymous">

</head>
<body>
	<nav class="navbar navbar-expand-lg navbar-light bg-light">
	  <div class="container-fluid">
	    <a class="navbar-brand" href="<?php echo URL ?>index1.php">LOGO</a>
	    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
	      <span class="navbar-toggler-icon"></span>
	    </button>
	    <div class="collapse navbar-collapse" id="navbarSupportedContent">
	      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
	        <li class="nav-item">
	          <a class="nav-link" aria-current="page" href="<?= URL ?>index1.php">Accueil</a>
	        </li>

	        <?php if( userConnect() ) :  //Si l'internaute est connecté, on affiche les liens 'profil' et 'deconnexion' ?>

		        <li class="nav-item">
		          <a class="nav-link" href="<?php echo URL ?>profil.php">Profil</a>
		        </li>
		        <li class="nav-item">
		          <a class="nav-link" href="<?php echo URL ?>connexion.php?action=deconnexion">Deconnexion</a>
		          <!-- la deconnexion sera gérée sur la page connexion.php -->
		        </li>

	        <?php else : //SINON, c'est que l'on est pas connecté donc on affiche les liens 'inscription' et 'connexion'> ?>

		        <li class="nav-item">
		          <a class="nav-link" href="<?php echo URL ?>inscription.php">Inscription</a>
		        </li>
		        <li class="nav-item">
		          <a class="nav-link" href="<?php echo URL ?>connexion.php">Connexion</a>
		        </li>

	    	<?php endif; //endif = représente l'accolade fermante de la condition if ?>

	        <li class="nav-item">
	          <a class="nav-link" href="<?php echo URL ?>panier.php">Panier</a>
	        </li>

	    	<?php if( adminConnect() ) : //SI l'ADMIN EST CONNECTE, on affiche le menu du BackOffice ?>

		        <li class="nav-item dropdown">
		          <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
		            Backoffice
		          </a>
		          <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
		            <li><a class="dropdown-item" href="<?= URL ?>admin/gestion_boutique.php">Gestion boutique</a></li>
		            <li><a class="dropdown-item" href="<?= URL ?>admin/gestion_membre.php">Gestion membre</a></li>
		            <li><a class="dropdown-item" href="<?= URL ?>admin/gestion_commande.php">Gestion commande</a></li>
		          </ul>
		        </li>

	    	<?php endif; ?>

	      </ul>
	    </div>
	  </div>
	</nav>

	<div class="container">

		