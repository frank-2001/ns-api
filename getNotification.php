<?php
// IdOrig=L'origine de l'article donc le vendeur
// IdDest=le client la personne qui commande l'article Destinataire 
	$user=$_GET['commandUser'];
	$id=explode(',', $_GET['commandUser']);
	$id=$id[1];
	$liv=getData('users',['id',$id],$bdd)[0]['livraison'];
	$retour['livraison']=$liv;
try { 
	$bdd;
	if ($liv==1) {
		$commande=$bdd->prepare("SELECT*FROM commande where custum=:param OR creator=:param OR state=0 order by id DESC");//Comande envoyer
	}else{
		$commande=$bdd->prepare("SELECT*FROM commande where custum=:param OR creator=:param order by id DESC");//Comande envoyer 
	}
	$commande->bindParam(':param',$user);
	$commande->execute();
	$listCmd=$commande->fetchAll();
	// print_r(count($listCmd));
	$retour["stateCmd"]=true;
	$retour["msgCmd"]="commande recu";
	$retour["nbCmd"]=count($listCmd);

	$notification= $bdd->prepare("SELECT*FROM notification where creator=:param order by inde DESC");//notification non lu
	$notification->bindParam(':param',$id);
	$notification->execute();
	$listNotif=$notification->fetchAll();
	$retour["stateNotif"]=true;
	$retour["msgNotif"]="commande envoyer";
	$retour["nbNotif"]=count($listNotif);
// Mettre dans un tableau le nom et l'id de l'user qui a liker et definir le temps selon la duree du post
	for ($i=0; $i < $retour["nbNotif"] ; $i++) { 
		$listNotif[$i]['user']=explode(',', $listNotif[$i]['user']);
		//Simplifie date pour notification heart subscribe
		$listNotif[$i]['day']=timeDo($listNotif[$i]['day']);
	}
	$retour["listNotif"]=$listNotif;
//
	for ($i=0; $i < $retour["nbCmd"] ; $i++) { 
		$listCmd[$i]['custum']=explode(',', $listCmd[$i]['custum']);
		$listCmd[$i]['creator']=explode(',', $listCmd[$i]['creator']);
		$listCmd[$i]['article']=explode(',', $listCmd[$i]['article']);
		$listCmd[$i]['adress']=explode(',', $listCmd[$i]['adress']);
		// Date simplifiee pour les commande 
		$listCmd[$i]['day']=timeDo($listCmd[$i]['day']);
	}
	$retour["listCmd"]=$listCmd;
}
catch(Exception $e){
	$retour["state"]=false;
	$retour["message"]="Erreur de connexion a la base de donnees";
}
// print_r($listCmd);

?>