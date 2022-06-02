<?php
try { 
	$bdd;
	$requete= $bdd->prepare("SELECT*FROM article where magasin=0");
	$requete->execute();
	$resultats=$requete->fetchAll();
	$retour["state"]=true;
	$retour["message"]="Articles d'occasion disponible";
	$retour["nb"]=count($resultats);
	$retour["resultat"]=$resultats;
	
}
catch(Exception $e){
	$retour["state"]=false;
	$retour["message"]="Erreur de connexion a la base de donnees";
}
?>