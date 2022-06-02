<?php
// if (token($_GET['auteur'],$_GET['token'],$bdd)['state']==1) {
$images=renameMoveImages($_GET['images'],'fichier/magasin/',$_GET['auteur']);
$reponse=$bdd->prepare('INSERT INTO magasin (nom, type, ville, adresse, description, id2, creation, profil) values (:nom, :type, :ville, :adresse, :description, :id2, :creation, :profil)');
$reponse->execute(array(
	'nom'=>$_GET['nom'],
	'type' =>$_GET['type'], 
	'ville'=>$_GET['ville'],
	'adresse'=>$_GET['adresse'],
	'description'=>$_GET['description'],
	'id2'=>$_GET['auteur'],
	'creation'=>time(),
	'profil'=>$images,
));
	$retour['message']="Magasin crée avec succès";
// }else{
// 	$retour['message']="Token Invalide";
// }
?>