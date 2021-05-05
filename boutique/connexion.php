<?php require_once "inc/header.inc.php"; ?>
<?php

//debug( $_GET );

if( isset( $_GET['action'] ) && $_GET['action'] == 'deconnexion' ){ 

	session_destroy();	 //detruit le fichier de session

}

//----------------------------------------------------------------
//restriction d'accès à la page :
if( userConnect() ){

	header('location:profil.php'); // redirection vers la page profil
	exit; 
}

//----------------------------------------------------------------
if( $_POST ){ 

	//debug( $_POST );

	//comparaison du pseudo posté et celui en BDD :
	$r = execute_requete(" SELECT * FROM membre WHERE pseudo = '$_POST[pseudo]' ");

	if( $r->rowCount() >= 1 ){ 

		$membre = $r->fetch( PDO::FETCH_ASSOC );
			//debug( $membre );

		
		if( password_verify( $_POST['mdp'] , $membre['mdp'] ) ){
		
			//arg1 : le mot de passe (ici, posté par l'internaute)
			//arg2 : la chaine crytée (par la fonction password_hash(), ici le mdp de la BDD)

			$_SESSION['membre'] = $membre;
			//debug( $_SESSION );

			//redirection sur la page de profil.php
			header('location:profil.php');
			exit(); 

		}
		else{ //SINON c'est que le mdp n'est pas bon

			$error .= "<div class='alert alert-danger'>Mot de passe incorrect</div>";
		}
	}
	else{ //SINON, c'est que le pseudo n'existe pas

		$error .= "<div class='alert alert-danger'>Pseudo incorrect</div>";
	}
}

//------------------------------------------------------------------------------------
?>
<h1>CONNEXION</h1>

<?= $error; ?>

<form method="post">
	
	<label>Pseudo</label><br>
	<input type="text" name="pseudo" placeholder="Votre pseudo"><br><br>

	<label>Mot de passe</label><br>
	<input type="text" name="mdp" placeholder="Votre mot de passe"><br><br>

	<input type="submit" value="Se connecter" class="btn btn-secondary">

</form>

<?php require_once "inc/footer.inc.php"; ?>