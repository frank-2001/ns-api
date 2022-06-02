<?php
try { 
	$bdd;
	if(isset($_GET['newArticle'])){
		
	}
	elseif(isset($_GET['articleMag'])){
		$requete= $bdd->prepare("SELECT * FROM article order by jour DESC where magasin=:id ");		
		$requete->bindParam(':id',$_GET['articleMag']);
		$requete->execute();
		$resultats=$requete->fetchAll();
		$retour["state"]=true;
		$retour["message"]="Articles d'un magasin";
		$retour["number"]=count($resultats);
		$retour["resultat"]=$resultats;
	}
	else{
	$requete= $bdd->prepare("SELECT * FROM article  order by rand()");
	$requete->execute();
	$resultats=$requete->fetchAll();
	$retour["state"]=true;
	$retour["message"]="Liste des articles disponible dans la base de donneess";
	$retour["number"]=count($resultats);
	for ($i=0; $i < $retour["number"]; $i++) { 
		$resultats[$i]['images']=explode(',',$resultats[$i]['images']);
		$resultats[$i]['auteur']=explode(',',$resultats[$i]['auteur']);
		$resultats[$i]['price']=explode(',',$resultats[$i]['price']);
		$resultats[$i]['prix']=explode(',',$resultats[$i]['prix']);
		$resultats[$i]['jour']=timeDo($resultats[$i]['jour']);

		if ($resultats[$i]['magasin']!='0') {
		$resultats[$i]['magasin']=explode(',',$resultats[$i]['magasin']);
		}
		// Avoir une image aleatoire au debut du tableau
		$alea=rand(0,count($resultats[$i]['images'])-1);
		$imgA=$resultats[$i]['images'][0];
		$resultats[$i]['images'][0]=$resultats[$i]['images'][$alea];
		$resultats[$i]['images'][$alea]=$imgA;
	}
	$retour["resultat"]=$resultats;
	}
}
catch(Exception $e){
	$retour["state"]=false;
	$retour["message"]="Erreur de connexion a la base de donnees";
}
?>