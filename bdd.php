<?php 
#ce sccript connecte le site la basse des donnees
try { 
#Base des donnees en ligne
	// $bdd = new PDO('mysql:host=databases.000webhost.com ;dbname=id19308017_nstoredb', 'id19308017_franky', 'B]YLDz601WF73tS4');
#Base des donnes local
	$bdd = new PDO('mysql:host=localhost;dbname=nsdb', 'root', ''); 
}

catch   (PDOException $pe)
    {
    die ("I cannot connect to the database " . $pe->getMessage());
}
?>