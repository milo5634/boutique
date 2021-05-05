<?php require_once "inc/header.inc.php"; ?>
<?php
//debug( $_POST );

if( isset( $_POST['ajout_panier'] ) ){ //Ici, on vérifie l'existence d'un "submit" dans 'fiche_produit.php' ('ajout_panier' provient de l'attribut 'name' de l'input submit du formulaire dans fiche_produit.php) DONC lorsque l'on ajoute un produit au panier

	$r = execute_requete(" SELECT titre, prix FROM produit WHERE id_produit = '$_POST[id_produit]' ");
	//Ici, $_POST['id_produit'] provient de l'input type='hidden' dans le form de fiche_produit.php

	$produit = $r->fetch( PDO::FETCH_ASSOC );
		//debug( $produit );

	ajout_panier( $produit['titre'], $_POST['id_produit'], $_POST['quantite'], $produit['prix'] );
}

//---------------------------------------------------------
 debug( $_SESSION );
// debug( $_POST );

if( isset( $_POST['payer'] ) ){ //Si on a cliqué sur le bouton "payer" (submit)

	$id_membre_connecte = $_SESSION['membre']['id_membre'];
	$total = montant_total();

	//insertion de la commande :
	$pdo->exec(" INSERT INTO commande( id_membre, montant, date ) 

				VALUES( $id_membre_connecte , $total , NOW() )
			");

	//récupération du numéro de commande : lastInsertId()
	$id_commande = $pdo->lastInsertId();
		//lastInsertId() : permet de récupérer le dernier id généré lors d'une insertion. Cette méthode est utilisable uniquement sur un objet PDO !

	$content .= "<div class='alert alert-success'>Merci pour votre commande, le numéro de la commande est le $id_commande</div>";

	//insertion du detail de la commande dans la table 'details_commande' (for) 
	for( $i = 0; $i < sizeof( $_SESSION['panier']['id_produit']); $i++ ){

		execute_requete(" INSERT INTO details_commande( id_commande, id_produit, quantite, prix) 

						VALUES( $id_commande,
								". $_SESSION['panier']['id_produit'][$i] .",
								". $_SESSION['panier']['quantite'][$i] .",
								". $_SESSION['panier']['prix'][$i] ."
						)
					");

		//modification du stock en conséquence de la commande (update) :
		execute_requete(" UPDATE produit SET 

							stock = stock - ". $_SESSION['panier']['quantite'][$i] ."


							WHERE id_produit = ". $_SESSION['panier']['id_produit'][$i]."
					");
	}

	//Vider le panier
	unset( $_SESSION['panier'] );
}


//---------------------------------------------------------
//debug( $_GET );

if( isset( $_GET['action'] ) && $_GET['action'] == 'vider' ){

	unset( $_SESSION['panier'] );
	//unset() : permet de détruire une variable (ici, $_SESSION['panier'] => revient à vider le panier)
}

//---------------------------------------------------------
//Affichage du contenu du panier :
$content .= '<table class="table" >';
	$content .= "<tr>
					<th>Titre</th>
					<th>Quantite</th>
					<th>prix</th>
				</tr>";

	if( empty( $_SESSION['panier']['id_produit'] ) ){ //SI la session/panier/id_produit est vide, c'est que je n'ai rien dans mon panier

		$content .= "<tr>
						<td colspan='4'> Votre panier est vide </td>
					</tr>";
	}
	else{ //SINON, c'est qu'il y a des produits dans le panier donc on les affiche

		for( $i = 0; $i < sizeof( $_SESSION['panier']['id_produit']); $i++ ){

			$content .= "<tr>";
				$content .= "<td>". $_SESSION['panier']['titre'][$i] ."</td>";
				$content .= "<td>". $_SESSION['panier']['quantite'][$i] ."</td>";

				//Ici, on mulitplie le prix selon la quantite :
				$prix_total = $_SESSION['panier']['prix'][$i] * $_SESSION['panier']['quantite'][$i];

				$content .= "<td>". $prix_total ." €</td>";

			$content .= "</tr>";
		}
		//--------------------------------------------
		//Montant total du panier :
		$content .= "<tr>
						<td colspan='2'>&nbsp;</td>
						<th colspan='2'>". montant_total() ." €</th>
					</tr>";

		//--------------------------------------------
		//Valider le panier :
		if( userConnect() ){ //SI l'utilisateur est connecté, on affiche un bouton pour valider le panier

			$content .= '<form method="post">';
				$content .= "<tr>";
					$content .= "<td>";

						$content .= '<input type="submit" name="payer" value="Payer" class="btn btn-secondary">';

					$content .= "</td>";
				$content .= "</tr>";
			$content .= '</form>';

		}
		else{ //Sinon, c'est que l'on est pas connecté, on affiche des liens pour que l'internaute se connecte ou s'inscrive.

			$content .= "<tr>";
				$content .= "<td colspan='4'>";

					$content .= "Veuillez vous <a href='connexion.php'>connecter</a> ou  vous <a href='inscription.php'>inscrire</a>.";

				$content .= "</td>";
			$content .= "</tr>";
		}

		//--------------------------------------------
		//VIDER LE PANIER :
		$content .= "<tr>";
			$content .= "<td>";

				$content .= "<a href='?action=vider'> Vider le panier </a>";

			$content .= "</td>";
		$content .= "</tr>";

	}
$content .= "</table>";

//-----------------------------------------------------------------------------------------------------------------
?>
<h1>PANIER</h1>

<?php echo $content; ?>

<?php require_once "inc/footer.inc.php"; ?>