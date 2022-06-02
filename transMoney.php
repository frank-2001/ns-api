<?php
//Transfert d'argent d'un compte vers un autre
$good=0;//Verificateur
//Token verification
if(token($_GET['send'],$_GET['token'],$bdd)['state']==1){

	// Arrange le numero de l'utilisateur
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
//Data Sender
$requete = $bdd->query('SELECT * FROM users where id=\'' . $_GET['send'] .'\'');
	$requete->execute();
	$sender=$requete->fetchAll();
//Data receiver
$requete = $bdd->query('SELECT * FROM users where telephones=\'' . $phone .'\'');
	$requete->execute();
	$receiver=$requete->fetchAll();
//Confirmation de l'existance des utilisateurs
if (count($receiver)==1 && count($sender)==1 && $sender[0]['telephones']!=$receiver[0]['telephones']) {
//echo '1. Good user and <> de some -- ';
	$dataCorrect=false;
	//Cle de cryptage et decryptage
	$keyReceiver=openssl_encrypt($receiver[0]['id'],"AES-128-ECB",'nunua-store');//Creation key de cryptage 
	$keySender=openssl_encrypt($sender[0]['id'],"AES-128-ECB",'nunua-store');//Creation key de cryptage 
	//Detection du devise Franc Congolais ou Dollars et verification de la somme en envoyer
	if($_GET['devise']=='Fc') {
		$senderDol=openssl_decrypt($sender[0]['walletFc'], "AES-128-ECB" ,$keySender);
		$receiverDol=openssl_decrypt($receiver[0]['walletFc'], "AES-128-ECB" ,$keyReceiver);
	}
	elseif ($_GET['devise']=='$') {
		$senderDol=openssl_decrypt($sender[0]['walletdol'], "AES-128-ECB" ,$keySender);
		$receiverDol=openssl_decrypt($receiver[0]['walletdol'], "AES-128-ECB" ,$keyReceiver);
	}
	else{
		$retour['message']="Devise non connue";
	}
	if($_GET['amount']<=$senderDol) {
	//Sender Update wallet 
		$senderDol=doubleval($senderDol)-doubleval($_GET['amount']);
		$senderDol=openssl_encrypt($senderDol, "AES-128-ECB" ,$keySender);
	//Receiver Update wallet
		$receiverDol=doubleval($receiverDol)+doubleval($_GET['amount']);
		$receiverDol=openssl_encrypt($receiverDol, "AES-128-ECB" ,$keyReceiver);
	//Crediter le compte apres detection du devise	
		if ($_GET['devise']=='$') {
	//Sender Update
		$select=array('id',$sender[0]['id']);
		updateDataByid('users','walletdol',$select,$senderDol,$bdd);
	//Receiver Update		
		$select=array('id',$receiver[0]['id']);
		updateDataByid('users','walletdol',$select,$receiverDol,$bdd);	
		$dataCorrect=true;				
		}
		if($_GET['devise']=='Fc'){
	//Sender Update
		$select=array('id',$sender[0]['id']);
		updateDataByid('users','walletFc',$select,$senderDol,$bdd);
	//Receiver Update
		$select=array('id',$receiver[0]['id']);
		updateDataByid('users','walletFc',$select,$receiverDol,$bdd);
		$dataCorrect=true;
		}

	}
	else{
		$retour['message']="Montant sortie superier au solde";
	}
	//Journal des transfers enregistrements		
	if ($dataCorrect==true) {
	$requete=$bdd->prepare('INSERT INTO journalmoney (send, receive, amount, day,type,state) VALUES 
	(:send, :receive, :amount, :day,:type,:state)');
		$requete->execute(array(
			'send'=>$sender[0]['id'],
			'receive'=>$receiver[0]['id'],
			'amount'=>$_GET['amount'].' '.$_GET['devise'],
			'day'=>time(),
			'type'=>'transfert',
			'state'=>'1',
			'token'=>openssl_encrypt(time(),"AES-128-ECB",'nunua-store'),

		));	
		$retour['message']="Transfert effectue avec succes";
		$retour['state']=true;
		$good=1;
	}
}
else{
$retour['state']=false;
if ($sender[0]['telephones']==$receiver[0]['telephones']) {
	$retour['message']="Vous ne pouvez pas envoyer de l'argent a ce numero";
}
else{
$retour['message']="L'utilisateur n'existe pas";
}
// //echo '6. Users n\'existe pas ou existe 2 fois ou receiver=sender';
}
}
else{
$retour['state']=false;
$retour['message']="Token Expire";
}
?>