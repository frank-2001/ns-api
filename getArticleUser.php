<?php
try { 
	$bdd;
	$requete= $bdd->prepare("SELECT*FROM article where id2=:id order by id DESC");
	$requete->bindParam(':id',$_GET['articleUser']);
	$requete->execute();
	$resultats=$requete->fetchAll();
	$retour["state"]=true;
	$retour["message"]="Liste des articles d'un utilisateur";
	$retour["number"]=count($resultats);
	$retour["resultat"]=$resultats;
}
catch(Exception $e){
	$retour["state"]=false;
	$retour["message"]="Erreur de connexion a la base de donnees";
}
?>