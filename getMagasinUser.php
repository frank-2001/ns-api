<?php
	$trie=array('id','nom','adresse','id2','description','profil');
	$resultats=getDataById(0,'magasin',array($trie[rand(0,5)],''),$bdd);
	for ($i=0; $i <count($resultats) ; $i++) { 
		$resultats[$i]['profil']=explode(',', $resultats[$i]['profil']);
	}
	$retour["state"]=true;
	$retour["message"]="Liste des magasins";
	$retour["resultat"]=$resultats;
?>