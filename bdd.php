<?php 
#ce sccript connecte le site la basse des donnees
try { 
#Base des donnees en ligne
	# $bdd = new PDO('mysql:host=localhost;dbname=microsop_nunua-store', 'microsop_franky', 'Code@2001'); }
#Base des donnes local
	$bdd = new PDO('mysql:host=localhost;dbname=nsdb', 'root', ''); }

	catch(Exception $e){
    die('Erreur : '.$e->getbreve()); 
	}?>