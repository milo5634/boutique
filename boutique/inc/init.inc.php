<?php
//Création/ouverture du fichier de session
session_start();
//PREMIERE LIGNE DE CODE, se positionne en haut et en premier avant tout traitement php !

//-----------------------------------------
//Connexion à la BDD :
$pdo = new PDO('mysql:host=localhost;dbname=boutique', 'root', '', array(PDO::ATTR_ERRMODE=>PDO::ERRMODE_WARNING, PDO::MYSQL_ATTR_INIT_COMMAND=>"SET NAMES UTF8") );

//var_dump($pdo);

//-----------------------------------------
//definition d'une constante :
define( 'URL', 'http://localhost/PHP/boutique/' ); //L'url de la racine du projet 'boutique'

//-----------------------------------------
//definition de variables :
$content = ''; //variable prévue pour recevoir le contenu
$error = ''; //variable prévue pour recevoir les messages d'erreurs

//-----------------------------------------
//Inclusion du fichier fonction.inc.php 
require_once "fonction.inc.php";


