<?php

function connexion($server,$bd,$login,$mdp) { // fonction pour se connecter a la base de donnée
	
	$pdo = new PDO('mysql:host='.$server.';dbname='.$bd,$login,$mdp) or die ("Attention, problème de connexion serveur");	
	return $pdo;
	
}

function affichertable(){ // Fonction pour afficher les tables
    try {
        // On créer la connexion du dur
        $pdo = connexion("localhost","test_spreadsheet_luis","root","@Aqw753+-/*");
        // On écrit la requete souhaité
        $query01 = "SELECT * FROM clients";
        // A SUIVRE !!!!!!!
    } catch (PDOExceptions $e) {			
		echo "Une erreur s'est produite lors de la connexion à la base de données";		
    }
}

?>