<?php
try { 
	$bdd;
	$requete= $bdd->prepare("SELECT*FROM article where type=:param");
	$requete->bindParam(':param',$_GET['trieType']);
	$requete->execute();
	$resultats=$requete->fetchAll();
	$retour["state"]=true;
	$retour["message"]="Articles du type ".$_GET['trieType'];
	$retour["nb"]=count($resultats);
	$retour["resultat"]=$resultats;
	
}
catch(Exception $e){
	$retour["state"]=false;
	$retour["message"]="Erreur de connexion a la base de donnees";
}
?>