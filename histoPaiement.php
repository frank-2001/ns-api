<?php
$requete = $bdd->query('SELECT * FROM journalmoney where send=\'' . $_GET['histo'] .'\' OR receive=\'' . $_GET['histo'].'\' order by id DESC');//Recuperation des information du compte
	$requete->execute();
	$resultat=$requete->fetchAll();
$format = "d M Y à H:i";
for ($i=0; $i <count($resultat) ; $i++) { 
	$resultat[$i]['day']=date($format, $resultat[$i]['day']);	
}
$retour['resultat']=$resultat;
?>