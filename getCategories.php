<?php
try { 
	$bdd;
	$requete= $bdd->prepare("SELECT title FROM categories order by rand()");
	$requete->execute();
	$resultats=$requete->fetchAll();
	$retour["state"]=true;
	$retour["message"]="Toutes les categories disponibles des articles";
	$retour["nb"]=count($resultats);
	$retour["resultat"]=$resultats;
}
catch(Exception $e){
	$retour["state"]=false;
	$retour["message"]="Erreur de connexion a la base de donnees";
}
?>