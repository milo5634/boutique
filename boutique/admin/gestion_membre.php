<?php require_once '../inc/header.inc.php'; ?>
<?php

//restriction d'accès à la page d'admin
if( !adminConnect() ){ //SI l'admin N'EST PAS connecté

	//redirection vers la page de connexion
	header('location:../connexion.php');
	exit();
}

//---------------------------------------------------
//SUPPRESSION :
//debug( $_GET );

if( isset( $_GET['action'] ) && $_GET['action'] == 'suppression' ){ //S'il existe une 'action' dans l'URL ET QUE cette 'action' est égale à 'suppression'

	execute_requete(" DELETE FROM membre WHERE id_membre = '$_GET[id_membre]' ");
}

//---------------------------------------------------
//affichage des membres
if( isset($_GET['action']) && $_GET['action'] == 'affichage' ) : //SI il y a une 'action' dans l'URL  ET que cette 'action' est égale à 'affichage', on affiche la liste des membres sous forme de tableau

	// 1etape : on récupère les infos dans la bdd de la table 'membre' :
	$pdostatement = execute_requete(" SELECT * FROM membre ");

	$content .= "<table border='2' cellpadding='5'>";
		$content .= '<tr>';

			for( $i = 0; $i < $pdostatement->columnCount(); $i++ ){

				$entete = $pdostatement->getColumnMeta( $i );
				//debug( $entete );

				if( $entete['name'] != 'mdp' ){ //SI le nom de la colonne est différent de 'mdp', alors on affiche les valeurs
					
					$content .= "<th> $entete[name] </th>";
				}
			}
			$content .= "<th>Suppression</th>"; 
			$content .= "<th>Modification</th>"; 
		$content .= '</tr>';

		//2 etape : Exploitation des données:
		while( $membre = $pdostatement->fetch( PDO::FETCH_ASSOC ) ){

			$content .= "<tr>";
				//debug( $membre );

				foreach( $membre as $index => $valeur ){

					if( $index != 'mdp' ){ // Si l'indice est différent de 'mdp' alors on affiche les valeurs
						
						$content .= "<td> $valeur </td>";
					}	
				}
				$content .= '<td class="text-center">
								<a href="?action=suppression&id_membre='. $membre['id_membre'] .'" onclick="return( confirm(\'En etes vous certain ?\'))" >
									<i class="fas fa-trash-alt"></i>
								</a>
							</td>';

				$content .= "<td class='text-center'>
								<a href='?action=modification&id_membre=$membre[id_membre]'>
									<i class='far fa-edit'></i>
								</a>
							</td>";

			$content .= "</tr>";
		}
	$content .= "</table>";

endif;
//-----------------------------------------------------------------------------------------------------
?>
<h1>GESTION MEMBRE</h1>

<a href="?action=affichage">Affichage des membres</a>

<?php echo $content ?>

<?php if( isset( $_GET['action'] ) && $_GET['action'] == 'modification') : //SI il y a une 'action dans l'URL  ET que cette 'action' est égale à 'modification', on affiche le formulaire que l'on va pré-remplir pour effectuer la modification 

	$pdostatement = execute_requete(" SELECT * FROM membre WHERE id_membre = '$_GET[id_membre]' ");

	$membre_a_modifier = $pdostatement->fetch( PDO::FETCH_ASSOC );
		//debug( $membre_a_modifier );

	$pseudo = $membre_a_modifier['pseudo'];
	$nom = $membre_a_modifier['nom'];
	$prenom = $membre_a_modifier['prenom'];
	$email = $membre_a_modifier['email'];
	$sexe = $membre_a_modifier['sexe'];
	$ville = $membre_a_modifier['ville'];
	$cp = $membre_a_modifier['cp'];
	$adresse = $membre_a_modifier['adresse'];
	$statut = $membre_a_modifier['statut'];

	//MODIFICATION:
	debug( $_POST );

	if( $_POST ){ //Si on valide le formulaire

		execute_requete("  UPDATE membre SET 	pseudo = '$_POST[pseudo]',
												nom = '$_POST[nom]',
												prenom = '$_POST[prenom]',
												email = '$_POST[email]',
												sexe = '$_POST[sexe]',
												ville = '$_POST[ville]',
												cp = '$_POST[cp]',
												adresse = '$_POST[adresse]',
												statut = '$_POST[statut]'

							WHERE id_membre = '$_GET[id_membre]'
						");
		
		//redirection vers l'affichage
		header('location:?action=affichage');
	}

?>

<form method="post">
	<label>Pseudo</label><br>
	<input type="text" name="pseudo" value="<?= $pseudo ?>"><br>

	<label>Prenom</label><br>
	<input type="text" name="prenom" value="<?= $prenom ?>"><br>

	<label>Nom</label><br>
	<input type="text" name="nom" value="<?= $nom ?>"><br>

	<label>Email</label><br>
	<input type="text" name="email" value="<?= $email ?>"><br>

	<label>Civilite</label><br>
	<input type="radio" name="sexe" value="f" <?php echo ( $sexe == 'f' ) ? 'checked': ''; ?>  >Femme<br>

	<input type="radio" name="sexe" value="m" <?php if( $sexe == 'm' ) echo 'checked'; ?>  >Homme<br><br>

	<label>Adresse</label><br>
	<input type="text" name="adresse" value="<?= $adresse ?>"><br>

	<label>Ville</label><br>
	<input type="text" name="ville" value="<?= $ville ?>"><br>

	<label>Code postal</label><br>
	<input type="text" name="cp" value="<?= $cp ?>"><br><br>

	<label>Statut</label><br>
	<select name="statut">
		<option value="0"  <?php if( $statut == 0 ) echo 'selected'; ?>  >Membre</option>
		<option value="1"  <?php if( $statut == 1 ) echo 'selected'; ?>  >Admin</option>
	</select><br><br>

	<input type="submit" value="Modifier" class="btn btn-secondary">
</form>

<?php endif; ?>

<?php require_once '../inc/footer.inc.php'; ?>