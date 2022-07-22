<?php 
#ce sccript connecte le site la basse des donnees
try { 
	if ($_SERVER['SERVER_NAME']=="localhost") {
		#Base des donnes local
		$bdd = 	new PDO('mysql:host=localhost;dbname=nsdb', 'root', ''); 
	}else{
		#Base des donnees en ligne
		$bdd = new PDO('mysql:host=sql.freedb.tech;dbname=freedb_nstore-db', 'freedb_franky', 'b2pXgQgE*cQgG6d');
	}
}

catch   (PDOException $pe)
    {
    die ("I cannot connect to the database " . $pe->getMessage());
}
?>