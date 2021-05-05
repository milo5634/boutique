<?php require_once "inc/header.inc.php"; ?>
<?php

//debug( $_GET );

if( isset( $_GET['id_produit'] ) ){
	
	$r = execute_requete(" SELECT * FROM produit WHERE id_produit = '$_GET[id_produit]' ");
	
}
else{ //SINON, on redirige vers la page d'accueil (si on essaie de forcer l'accès à la page via l'url et qu'il n'y a pas d'id_produit)

	header('location:index1.php');
	exit();
}
//------------------------------------------------------------
//Exploitation des données récupérées :
$produit = $r->fetch( PDO::FETCH_ASSOC );
	//debug( $produit );

$content .= "<a href='index1.php'>Retour page d'accueil</a><br>";

$content .= "<a href='index1.php?categorie=$produit[categorie]'>
				Retour vers la categorie $produit[categorie] 
			</a><hr>";

foreach( $produit as $index => $valeur ){ 

	if( $index == 'photo' ){ 

		$content .= "<p><img src='$valeur' width='200' ></p>";
	}
	elseif( $index != 'id_produit' && $index != 'stock' ){ 

		$content .= "<p><strong>$index</strong> : $valeur </p>";
	}
}

//------------------------------------------------------------
//Gestion du stock et du panier :
if( $produit['stock'] > 0 ){

	$content .= "<p>Nombre de produits disponibles : $produit[stock]</p>";

	$content .= "<form method='post' action='panier.php'>";
	//Ici, l'attribut action='panier.php' : permet d'etre redirigé sur le fichier panier.php lorsque je valide le formulaire. Les données du formulaire seront donc traitées sur le fichier panier.php

		$content .= "<input type='hidden' name='id_produit' value='$produit[id_produit]'>";
		//Ici, on a un input "caché" qui permet d'envoyer l'id du produit que l'on souahite ajouté au panier qui servira à récupérer toutes les infos du produit sur le fichier panier.php

		$content .= "<label>Quantité</label>";
		$content .= "<select name='quantite'>";

			for( $i = 1; $i <= $produit['stock']; $i++ ){

				$content .= "<option value='$i'> $i </option>";
			}

		$content .= "</select><br><br>";

		$content .= "<input type='submit' name='ajout_panier' value='Ajouter au panier' class='btn btn-secondary'>";

	$content .= "</form>";

}
else{ //SINON, c'est que le stock est à zéro

	$content.= "<p>Rupture de stock !</p>";
}

//------------------------------------------------------------------------------------------------------
?>
<h1>FICHE PRODUIT</h1>

<?= $content; ?>

<?php require_once "inc/footer.inc.php"; ?>