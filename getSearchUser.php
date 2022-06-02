<?php
try { 
	$bdd;
	$requete= $bdd->prepare('SELECT*FROM users where names LIKE \'%' . $_GET['search'] . '%\' ');
	$requete->bindParam(':look',$_GET['search']);
	$requete->execute();
	$resultats=$requete->fetchAll();
	$retour["state"]=true;
	$retour["message"]="Resultat recherche ".$_GET['search'];
	$retour["nb"]=count($resultats);
	$retour["resultat"]=$resultats;
	
}
catch(Exception $e){
	$retour["state"]=false;
	$retour["message"]="Erreur de connexion a la base de donnees";
}
?>