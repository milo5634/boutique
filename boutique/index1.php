<?php require_once "inc/header.inc.php"; ?>
<?php 
//Affichage des produits :

$r = execute_requete(" SELECT DISTINCT categorie FROM produit ");

$content .= '<div class="row">';

	//affichage des catégories : 
	$content .= "<div class='col-3'>";
		$content .= "<div class='list-group-item' >";

			while( $categorie = $r->fetch( PDO::FETCH_ASSOC ) ){

				//debug( $categorie );
				$content .= "<a href='?categorie=$categorie[categorie]' class='list-group-item'>
								$categorie[categorie]
							</a>";
			}
		$content .= "</div>";
	$content .= "</div>";

	//affichage des produits correspondants à la catégorie cliquée :
	//debug( $_GET );

	$content .= "<div class='col-8 offset-1'>";
		$content .= "<div class='row'>";

			if( isset( $_GET['categorie'] ) ){ 

				$r = execute_requete(" SELECT * FROM produit WHERE categorie = '$_GET[categorie]' "); 
				
				while( $produit = $r->fetch( PDO::FETCH_ASSOC ) ){

					//debug( $produit );

					$content .= "<div class='col-2'>";
						$content .= "<div class='thumbnail' style='border:1px solid #eee'>";

							$content .= "<a href='fiche_produit.php?id_produit=$produit[id_produit]'>";
							//Ici, je crée un lien <a> pour accéder au détails du produit donc accéder au fichier fiche_produit.php et pour récupéré les infos du produit sur lequel on a cliqué, je fais apsser l'id_produit du produit concerné dans l'url

								$content .= "<img src='$produit[photo]' width='80' >";
								$content .= "<p> $produit[titre]</p>";
								$content .= '<p>' . $produit['prix'] .'</p>';

							$content .= "</a>";

						$content .= "</div>";
					$content .= "</div>";
				}
			}
		$content .= "</div>";
	$content .= "</div>";
$content .= '</div>';
//-------------------------------------------------------------------------------------------------------------
?>
<h1>ACCUEIL SITE BOUTIQUE</h1>

<?= $content; ?>

<?php require_once "inc/footer.inc.php"; ?>