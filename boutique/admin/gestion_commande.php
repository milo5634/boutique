<?php require_once '../inc/header.inc.php'; ?>
<?php
//restriction d'acces à la page admin:
if( !adminConnect() ){

	header('location:../connexion.php');
	exit();
}

//-------------------------------------------------------------------------------
//Affichage des commandes (sous forme de tableau)

$info_commande = execute_requete("  
									SELECT c.*, m.pseudo, m.adresse, m.ville, m.cp   

									FROM commande as c, membre as m

									WHERE c.id_membre = m.id_membre
								");   // les "as" sont fait pour raccourcir l'écriture des nom de tables. Les préciser est fait pour le cas ou il y aurait des doubles dans chaque tables

$content .= "Nombre de commandes : " . $info_commande->rowCount();

$content .= '<table border="2" cellpadding="5">';
	$content .= '<tr>';
		for( $i = 0; $i < $info_commande->columnCount(); $i++ ){

			$entete = $info_commande->getColumnMeta( $i );
				//debug( $entete );

			$content .= "<th>$entete[name]</th>";
		}
	$content .= '</tr>';

	$chiffre_affaire = 0; //definition d'une variable a zero

	while( $ligne = $info_commande->fetch( PDO::FETCH_ASSOC ) ){

		$chiffre_affaire += $ligne['montant'];

		$content .= '<tr>';
			//debug( $ligne );
			foreach( $ligne as $index => $valeur ){

				if( $index == 'id_commande'){

					$content .= '<td>';

						$content .= "<a href='?suivi=$valeur'>Voir la commande $valeur</a>";

					$content .= '</td>';
				}
				else{

					$content .= "<td>$valeur</td>";
				}
			}
		$content .= '</tr>';
	}
$content .= '</table>';

$content .= "<p>CA de la boutique : $chiffre_affaire €</p>";

//---------------------------------------------------------------------------
//affichage du detail de la commande :
//debug( $_GET );
if( isset($_GET['suivi']) ){ //SI on a cliqué sur "voir la commande "

	$content .= "<h3>Voici le détails de la commande n° $_GET[suivi]</h3>";

	$detail_commande = execute_requete(" 

				SELECT d.*, p.titre 
				FROM details_commande as d, produit as p
				WHERE d.id_commande = '$_GET[suivi]' 
				AND d.id_produit = p.id_produit 

				");

	$content .= '<table border="2" cellpadding="5">';
		$content .= '<tr>';
			for( $i = 0; $i < $detail_commande->columnCount(); $i++ ){

				$entete = $detail_commande->getColumnMeta( $i );
					//debug( $entete );

				$content .= "<th>$entete[name]</th>";
			}
		$content .= '</tr>';

		while( $ligne = $detail_commande->fetch( PDO::FETCH_ASSOC ) ){

			$content .= '<tr>';
				//debug( $ligne );
				foreach( $ligne as $index => $valeur ){

					$content .= "<td>$valeur</td>";	
				}
			$content .= '</tr>';
		}
	$content .= '</table>';
}

?>

<h1>Gestion des commandes</h1>

<?= $content ?>

<?php require_once '../inc/footer.inc.php'; ?>