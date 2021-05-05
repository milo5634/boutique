<?php require_once "../inc/header.inc.php"; ?>
<?php

//restriction d'accès à la page adminsitrative
if( !adminConnect() ){ //SI l'admin N'EST PAS connecté, on le redirige vers la page de connexion

	header('location:../connexion.php');
	exit;
}

//-------------------------------------------------------------------------------------------------
//SUPPRESSION :
if( isset( $_GET['action'] ) && $_GET['action'] == 'suppression' ){ 

	//SUPPRESSION DE LA PHOTO :
	//récupération de la colonne
	$pdostatement = execute_requete(" SELECT photo FROM produit WHERE id_produit = '$_GET[id_produit]' ");

	$photo_a_supprimer = $pdostatement->fetch( PDO::FETCH_ASSOC );
		//debug( $photo_a_supprimer ); 

	$chemin_photo_a_supprimer = str_replace( 'http://localhost', $_SERVER['DOCUMENT_ROOT'], $photo_a_supprimer['photo'] );
		//debug( $chemin_photo_a_supprimer );

		
	if( !empty( $chemin_photo_a_supprimer ) && file_exists( $chemin_photo_a_supprimer ) ){ 

		unlink( $chemin_photo_a_supprimer );
		//unlink( url ) : permet de supprimer un fichier
	}

	execute_requete(" DELETE FROM produit WHERE id_produit = '$_GET[id_produit]' ");

}

//-------------------------------------------------------------------------------------------------
//Gestion des produits : INSERTION et MODIFICATION
if( !empty( $_POST ) ){ 
	
	//debug( $_POST );

	$pdostatement = execute_requete(" SELECT reference FROM produit WHERE reference = '$_POST[reference]' ");

	if( $pdostatement->rowCount() >= 1 ){

		$error .= "<div class='alert alert-danger'>La référence est déjà utilisée</div>";
	}

	foreach( $_POST as $index => $valeur ){

		$_POST[$index] = htmlentities( addslashes( $valeur ) );
	}

	//------------------------------------------------
	//GESTION de la photo :
	//debug( $_FILES );
	//debug( $_SERVER );

	if( isset($_GET['action']) && $_GET['action'] == 'modification' ){

		$photo_bdd = $_POST['photo_actuelle'];
	}

	//------------------------------------------------
	if( !empty( $_FILES['photo']['name'] ) ){ 

		//Ici, je renomme la photo (avec la référence) :
		$nom_photo = $_POST['reference'] . '_' . $_FILES['photo']['name'];
			//debug( $nom_photo );

		//Chemin pour accéder à la photo (à insérer en BDD) :
		$photo_bdd = URL . "photo/$nom_photo";
			//debug( $photo_bdd );

		//Ou est ce que l'on souhaite enregistrer le fichier 'physique' de la photo :
		$photo_dossier = $_SERVER['DOCUMENT_ROOT'] . "/PHP/boutique/photo/$nom_photo";
 			//debug( $photo_dossier );

 		//Enregistrement de la photo au bon endroit, ici dans le dossier photo de notre serveur
			copy( $_FILES['photo']['tmp_name'], $photo_dossier );
			//copy( arg1, arg2 );
				//arg1 : chemin du fichier source
				//arg2 : chemin de destination
	}
	else{ //SINON, on affiche un message d'erreur

		$error .= "<div class='alert alert-danger'>Vous n'avez pas uploader de photo !</div>";
	}

	//------------------------------------------------
	//MODIFICATION & INSERTION :
	if( isset($_GET['action']) && $_GET['action'] == 'modification' ){ 

		execute_requete("   UPDATE produit SET 	reference = '$_POST[reference]',
												categorie = '$_POST[categorie]',
												titre = '$_POST[titre]',
												description = '$_POST[description]',
												couleur = '$_POST[couleur]',
												taille = '$_POST[taille]',
												sexe = '$_POST[sexe]',
												photo = '$photo_bdd',
												prix = '$_POST[prix]',
												stock = '$_POST[stock]'

							WHERE id_produit = '$_GET[id_produit]'

						");

		//redirection vers l'affichage :
		header('location:?action=affichage');


	}
	elseif( empty( $error ) ){ //SINON (c'est que l'on est dans le cadre d'un ajout d'un produit) SI, la variable $error est vide, je fais mon insertion

		execute_requete(" INSERT INTO produit( reference, categorie, titre, description, couleur, taille, sexe, photo, prix, stock )

						VALUES(
								'$_POST[reference]',
								'$_POST[categorie]',
								'$_POST[titre]',
								'$_POST[description]',
								'$_POST[couleur]',
								'$_POST[taille]',
								'$_POST[sexe]',
								'$photo_bdd',
								'$_POST[prix]',
								'$_POST[stock]'
						) 
					");
	}
}

//-------------------------------------------------------------------------------------------------
//AFFICHAGE DES PRODUITS :
//debug( $_GET );

if( isset( $_GET['action'] ) && $_GET['action'] == 'affichage' ){ 

	//je récupère les produits en BDD :
	$pdostatement = execute_requete(" SELECT * FROM produit ");

	$content .= "<h2>Liste des produits</h2>";
	$content .= "<p>Nombre de produits dans la boutique : " . $pdostatement->rowCount() . "</p>";

	$content .= "<table class='table table-bordered' cellpadding='5'>";
		$content .= "<tr>";

			$nombre_colonne = $pdostatement->columnCount(); 
				//debug( $nombre_colonne );

			for( $i = 0; $i < $nombre_colonne; $i++ ){

				$info_colonne = $pdostatement->getColumnMeta( $i ); 
					//debug( $info_colonne );

				$content .= "<th> $info_colonne[name] </th>";
			}
			$content .= "<th>Suppression</th>";
			$content .= "<th>Modification</th>";

		$content .= "</tr>";

		while( $ligne = $pdostatement->fetch( PDO::FETCH_ASSOC ) ){
			//debug( $ligne );

			$content .= "<tr>";
				foreach( $ligne as $indice => $valeur ){

					if( $indice == 'photo' ){ //SI l'indice (du tableau $ligne) est égale à 'photo', alors on affiche une cellule avec une balise <img> et dans l'attribut src='' on y met la valeur correspondante ($valeur) et donc l'adresse pour accéder à l'image

						$content .= "<td> 
										<img src='$valeur' width='80'> 
									</td>";						
					}
					else{ //SINON, on affiche les valeurs dans une cellule simple

						$content .= "<td> $valeur </td>";
					}
				}
				$content .= '<td class="text-center">
								<a href="?action=suppression&id_produit='. $ligne['id_produit'] .'" onclick="return(confirm(\'Voulez-vous vraiment supprimer ce f**** produit ?\'));" >
									<i class="fas fa-trash-alt"></i>
								</a>
							</td>';
					//Ici, on fait passer via le lien <a href=""> des informations dans l'url : une action de suppression et l'id du produit que l'on souhaite supprimer

				$content .= '<td class="text-center">
								<a href="?action=modification&id_produit='. $ligne['id_produit'] .'" >
									<i class="far fa-edit"></i>
								</a>
							</td>';

			$content .= "</tr>";
		}
	$content .= "</table>";
}

//-------------------------------------------------------------------------------------------------
?>
<h1>GESTION BOUTIQUE</h1>

<a href="?action=ajout">Ajouter un nouveau produit</a><br>
<a href="?action=affichage">Affichage des produits</a><hr>

<?= $error; ?>
<?= $content; ?>

<?php if( isset($_GET['action']) && ( $_GET['action'] == 'ajout' || $_GET['action'] == 'modification' ) ) :  

	if( isset( $_GET['id_produit']) ){ 

		//récupération des infos à afficher pour pré-remplir le formulaire
		$pdostatement =	execute_requete(" SELECT * FROM produit WHERE id_produit = '$_GET[id_produit]' ");

		//exeploitation des données :
		$article_actuel = $pdostatement->fetch( PDO::FETCH_ASSOC );
			//debug( $article_actuel );
	}

	//------------------------------------
	//condition pour vérifier l'existance des variables :
	if( isset( $article_actuel['reference'] ) ){ 
		
		$reference = $article_actuel['reference']; //Ici, on stocke dans une variable la valeur récupérée en BDD que l'on affichera dans l'attribut value="" des inputs correspondants
	}
	else{ //SINON, c'est que je ne suis pas dans le cadre d'une modification et (mais d'un ajout !) alors je stocke du 'vide' dans la variable qui sera affiché dans l'attribut value="" des inputs correspondants

		$reference = '';
	}


	//taille (select/option)
	if( isset($article_actuel['taille']) && $article_actuel['taille'] == 'S' ){

		$taille_s = 'selected';
	}
	else{
		$taille_s = '';
	}

	$taille_m = ( isset($article_actuel['taille']) && $article_actuel['taille'] == 'M') ? 'selected' : '';
	$taille_l = ( isset($article_actuel['taille']) && $article_actuel['taille'] == 'L') ? 'selected' : '';
	$taille_xl = ( isset($article_actuel['taille']) && $article_actuel['taille'] == 'XL') ? 'selected' : '';

	//sexe (input/radio)
	$sexe_m = ( isset($article_actuel['sexe']) && $article_actuel['sexe'] == 'm' ) ? 'checked' : '';
	$sexe_f = ( isset($article_actuel['sexe']) && $article_actuel['sexe'] == 'f' ) ? 'checked' : '';

	//photo :
	if( isset( $article_actuel['photo'] ) ){ //SI il existe $article_actuel['photo'], c'est que je suis dans le cadre d'une modification et donc j'affiche l'image dans le formulaire pré-rempli

		$info_photo = '<i> Vous pouvez uploader une nouvelle photo </i>';

		$info_photo .= "<img src='$article_actuel[photo]' width='80px' ><br><br>";

		$info_photo .= "<input type='hidden' name='photo_actuelle' value='$article_actuel[photo]' >";
		//Ici, je crée un input type='hidden' donc 'caché'' avec en value l'adresse de la photo récupérée en bdd pour pouvoir la récupérer pour la modification si je ne télécharge pas une nouvelle photo.

	}
	else{ //SINON, j'affiche rien..

		$info_photo = '<br>';
	}

//---------------------------------------------------------------------------------------------
?>

<form method="post" enctype="multipart/form-data">

	<label>Référence</label><br>
	<input type="text" name="reference" value="<?php echo $reference ?>"><br><br>
	
	<label>Catégorie</label><br>
	<input type="text" name="categorie" value="<?= $categorie ?>"><br><br>

	<label>Titre</label><br>
	<input type="text" name="titre" value="<?= $titre ?>"><br><br>

	<label>Description</label><br>
	<input type="text" name="description" value="<?= $description ?>"><br><br>

	<label>Couleur</label><br>
	<input type="text" name="couleur" value="<?= $couleur ?>"><br><br>	

	<label>Taille</label><br>
	<select name="taille">
		<option value="S" <?= $taille_s ?>  > S </option>
		<option value="M" <?= $taille_m ?>  > M </option>
		<option value="L" <?= $taille_l ?>  > L </option>
		<option value="XL" <?= $taille_xl ?>  > XL </option>
	</select><br><br>

	<label>Civilité</label><br>
	<input type="radio" name="sexe" value="m" <?= $sexe_m ?> >Homme <br>
	<input type="radio" name="sexe" value="f" <?= $sexe_f ?> >Femme <br><br>

	<label>Photo</label><br>
	<input type="file" name="photo"><br>

		<?= $info_photo; //affichage de la photo ?>

	<label>Prix</label><br>
	<input type="text" name="prix" value="<?= $prix ?>"><br><br>	

	<label>Stock</label><br>
	<input type="text" name="stock" value="<?= $stock ?>"><br><br>	

	<input type="submit" value="<?= ucfirst($_GET['action']); ?>" class="btn btn-secondary">
</form>

<?php endif; ?>

<?php require_once "../inc/footer.inc.php"; ?>