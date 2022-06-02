<?php
$imgs=explode(',',$_GET['images']);
$auteur=explode(',',$_GET['auteur']);
if($_GET['magasin']!='0'){
	$mag=explode(',',$_GET['magasin']);
	$testOrig=getData('magasin',['id',$mag[1]],$bdd)[0]['id2'];
}else{
	$testOrig=0;
}
if($testOrig==$auteur[1] || $testOrig=='0'){

for ($i=0; $i < count($imgs); $i++) { 
$ext[$i]=explode('.',$imgs[$i]);
$nameImage[$i]='Nunua-store'.$i.''.$auteur[1].''.time().'.'.$ext[$i][1];
rename('fichier/Upload/'.$imgs[$i],'fichier/Upload/'.$nameImage[$i]);
}
if($_GET['devise']=="Fc"){
    $transp=0;
}
if($_GET['devise']=="$"){
    $transp=0;
}
$_GET['prix']=$_GET['prix']+$transp;
$requete=$bdd->prepare ('INSERT INTO article (article,description,jour,auteur,type,magasin,images,price,quantite) values (:article, :description,:jour, :auteur,:type,:magasin,:images,:price,:quantite)');
		$requete->execute(array(
			'article'=>$_GET['title'],
			'description'=>$_GET['description'],
			'jour'=>time(),
			'auteur'=>implode(',', $auteur),
			'magasin'=>$_GET['magasin'],
			'type'=>$_GET['categorie'],
			'images'=>implode(',',$nameImage),
			'price'=>$_GET['prix'].','.$_GET['devise'],
			'quantite'=>$_GET['qte'],
		));
$retour['message']="Publication ".$_GET['title']." faite avec succes!!";

}else{
	echo "Echec";
	$retour['message']="Echec, Le magasin ne vous appartiens pas";
}
?>