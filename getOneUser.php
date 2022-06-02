<?php
try { 
	$bdd;
	$requete= $bdd->prepare("SELECT*FROM users where id=:id");
	$requete->bindParam(':id',$_GET['oneUser']);
	$requete->execute();
	$resultats=$requete->fetchAll();
	$retour["state"]=true;
	$retour["message"]="Un utilisateur recupere grace a son ID";
	$retour["nb"]=count($resultats);
	$retour["resultat"]=$resultats;
	
}
catch(Exception $e){
	$retour["state"]=false;
	$retour["message"]="Erreur de connexion a la base de donnees";
}
?>