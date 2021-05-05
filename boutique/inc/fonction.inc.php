<?php
//fonction de debugage : (permet de faire un print_r() "amélioré")
function debug( $arg ){

	echo "<div style='background:#fda500; z-index:1000; padding:15px'>";

		$trace = debug_backtrace();
		//debug_backtrace() : fonction interne de php qui retourne un array avec des infos à l'endroit où l'on fait appel à elle.

		echo "Debug demandé dans le fichier : " . $trace[0]['file'] . " à la ligne " . $trace[0]['line'];

		print "<pre>";
			print_r( $arg );
		print "</pre>";

	echo "</div>";
}

//------------------------------------------
//Fonction pour exécuter les requêtes
function execute_requete( $req ){

	global $pdo;

	$pdostatement = $pdo->query( $req );

	return $pdostatement;
}

//------------------------------------------
//fonction userConnect() : si l'internaute est connecté on renvoie "true" si on n'est pas connecté on renvoie "false"
function userConnect(){

	if( !isset( $_SESSION['membre'] ) ){ 

		return false;
	}
	else{ //SINON, c'est que la session/membre existe et donc que l'on est connecté ! On renvoie true 

		return true;
	}
}

//------------------------------------------
//fonction adminConnect() : administreur est connecté :
function adminConnect(){

	if( userConnect() && $_SESSION['membre']['statut'] == 1 ){ //SI l'internaute est connecté ET QU'il est admin (donc que son statut est égal à 1 ) on renvoie true

		return true;
	}
	else{ //SINON, on renvoie false

		return false;
	}
}

//------------------------------------------
//fonction pour créer un panier :
function creation_panier(){

	if( !isset( $_SESSION['panier'] ) ){ //SI la session/panier N'EXISTE PAS, on la crée

		$_SESSION['panier'] = array();

			$_SESSION['panier']['titre'] = array();
			$_SESSION['panier']['id_produit'] = array();
			$_SESSION['panier']['quantite'] = array();
			$_SESSION['panier']['prix'] = array();
	}
}

//------------------------------------------
//Fonction d'ajout d'un produit au panier
function ajout_panier( $titre, $id_produit, $quantite, $prix ){

	creation_panier();
	//ici, on fait appel à la fonction déclarée ci-dessus
		//SOIT le panier n'existe pas et donc on la crée (LA première fois que l'on tente d'ajotuer un produit au panier)
		//SOIT il existe et on l'utilise (puisqu'on ne rentrera pas dans la condition de la fonction creation_panier())

	//Est ce que le produit existe déjà dans le panier ?
	$index = array_search( $id_produit, $_SESSION['panier']['id_produit'] );
	//array_search( arg1 , arg2 );
		//arg1 : ce que l'on cherche
		//arg2 : dans quel tableau on effectue la recherche
		//Valeur de retour : la fonction renverra la "clé" (correspondant à l'indice du tableau SI il y a une correspondance) ou "false"
		//debug( $index );

	if( $index !== false ){ //SI $index est strictement différent de 'false', c'est que le produit est déjà présent dans le panier et donc on va augmenté la quantité

		$_SESSION['panier']['quantite'][$index] += $quantite;
		//Ici, on va précisément à l'indice du produit déjà présent dans le panier et on y ajoute la nouvelle quantité
	}
	else{ //SINON, c'est que le produit n'est pas dans le panier et donc on insert toutes les infos nécessaires

		$_SESSION['panier']['titre'][] = $titre;
		$_SESSION['panier']['id_produit'][] = $id_produit;
		$_SESSION['panier']['quantite'][] = $quantite;
		$_SESSION['panier']['prix'][] = $prix;
		//Les crochets vides sont indispensables pour permettre d'ajouter une information à la fin du tableau et donc d'ajouter des produits au panier
	}
}

//------------------------------------------
//fonction montant total du panier :
function montant_total(){

	$total = 0;

	for( $i = 0; $i < sizeof( $_SESSION['panier']['id_produit']); $i++ ){

		$total += $_SESSION['panier']['quantite'][$i] * $_SESSION['panier']['prix'][$i];
		//A chaque tour de boucle (qui correspond au nombre de produit dans le panier), on ajoute le montant (quantite * prix ) pour chaque produit dans la variable $total
	}

	return $total;
}

