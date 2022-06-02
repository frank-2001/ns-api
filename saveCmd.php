<?php
// 2,3,4
$creator=explode(',', $_GET['creator'])[1];
$custum=explode(',', $_GET['custum'])[1];
$article=explode(',', $_GET['articleInfos']);
$amount=$article[2]*$article[3];
$devise=$article[4];
//Transfert de l'argent
$_GET['send']=$custum;
$_GET['devise']=$devise;
$_GET['amount']=$amount;
//Mettre de l'argent dans un  compte d'entente en attendand la confirmation
$_GET['receive']=243;
	if(token($_GET['send'],$_GET['token'],$bdd)['state']==1){
		$phone=$_GET['receive'];
	if (substr($phone,0,1)==0) {
		$numero=substr($phone,1);
	 	$phone='243'.$numero;
	}
	if (substr($phone,0,1)==9) {
	 	$phone='243'.$phone;
	}
	if (substr($phone,0,1)==8) {
	 $phone='243'.$phone;
	}
		//Enregistrement de la commande
	$requete=$bdd->prepare('INSERT INTO commande (creator, custum, article, day, adress) VALUES 
	(:creator, :custum, :article, :day, :adress)');
		$requete->execute(array(
			'creator'=>$_GET['creator'],
			'article'=>$_GET['articleInfos'],
			'day'=>time(),
			'custum'=>$_GET['custum'],
			'adress'=>$_GET['adress'],
		));
	$format = "d M Y à H:i";
	$day=date($format,time());
	$message=$day." - ".$custum[0]." commande ".$article[3]." ".$article[1]." de ".$article[2].''.$article[4]." adr Cl : ".$_GET['adress']." Vendeur ".$creator[0];
	// Tranert d'argent
	$retour=transMoney($_GET['send'],$phone,$_GET['amount'],$_GET['devise'],$bdd);
	require 'sms/index.php';
	}else{
		$retour['message']="Token invalide";
	}
?>