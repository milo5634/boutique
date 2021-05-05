<?php require_once "inc/header.inc.php"; ?>
<?php

//-----------------------------------------------------------------------
//restriction d'accès à la page inscription SI on est connecté :
if( userConnect() ){

	header('location:profil.php'); //redirection vers la page de profil.
	exit; 
}

//------------------------------------------------------------------------
if( $_POST ){ 

	//debug( $_POST );

	//Controles sur les saisies de l'internaute (il faudrait faire des controles pour TOUS les inputs du form)

	if( strlen( $_POST['pseudo'] ) <= 3 || strlen( $_POST['pseudo'] ) > 15 ){ 

		$error .= '<div class="alert alert-danger">Erreur taille pseudo</div>';
	}

		
	$r = execute_requete(" SELECT pseudo FROM membre WHERE pseudo = '$_POST[pseudo]' ");
		//var_dump( $r );

	if( $r->rowCount() >= 1 ){

		$error .= "<div class='alert alert-danger'>Pseudo indisponible</div>";
	}

	foreach( $_POST as $index => $valeur ){

		$_POST[$index] = htmlentities( addslashes( $valeur) );
	}

	
	$_POST['mdp'] = password_hash( $_POST['mdp'] , PASSWORD_DEFAULT);
	//password_hash() : permet de créer une clé de hachage
		//echo $_POST['mdp'];

	//INSERTION : 
	if( empty( $error ) ){ 

		execute_requete(" INSERT INTO membre( pseudo, mdp, nom, prenom, email, sexe, ville, adresse, cp ) 

						VALUES( 
								'$_POST[pseudo]',
								'$_POST[mdp]',
								'$_POST[nom]',
								'$_POST[prenom]',
								'$_POST[email]',
								'$_POST[sexe]',
								'$_POST[ville]',
								'$_POST[adresse]',
								'$_POST[cp]'
						)
					");

		$content .= "<div class='alert alert-success'>Inscription validée. 
						<a href='".URL."connexion.php'>Cliquez ici pour vous connecter</a>
					</div>";
	}
}

//---------------------------------------------------------------------------------------------------------
?>
<h1>INSCRIPTION</h1>

<?php echo $error; ?>

<?= $content; ?>

<form method="post">
	
	<label>Pseudo</label><br>
	<input type="text" name="pseudo"><br>
	
	<label>Mot de passe</label><br>
	<input type="text" name="mdp"><br>

	<label>Nom</label><br>
	<input type="text" name="nom"><br>

	<label>Prénom</label><br>
	<input type="text" name="prenom"><br>

	<label>Email</label><br>
	<input type="text" name="email"><br>

	<label>Civilite</label><br>
	<input type="radio" name="sexe" value="f" checked>Femme <br>
	<input type="radio" name="sexe" value="m" >Homme <br><br>

	<label>Adresse</label><br>
	<input type="text" name="adresse"><br>

	<label>Ville</label><br>
	<input type="text" name="ville"><br>

	<label>Code postal</label><br>
	<input type="text" name="cp"><br><br>

	<input type="submit" value="S'incrire" class="btn btn-secondary">
</form>

<?php require_once "inc/footer.inc.php"; ?>