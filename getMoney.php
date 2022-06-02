<?php 
//Crediter son compte et transfert d'argent
//Decryptage de infos recu
$_GET['devise']=decrypt($_GET['devise']);
$_GET['amount']=decrypt($_GET['amount']);
$_GET['send']=decrypt($_GET['send'].'');
// Script transfert de l'argent vers un autre compte
if (isset($_GET['receive'])) {
	$_GET['receive']=decrypt($_GET['receive']);
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
		$retour=transMoney($_GET['send'],$phone,$_GET['amount'],$_GET['devise'],$bdd);
	}else{
		$retour['message']="Token invalide";
	}
}
else{
	$_GET['id']=decrypt($_GET['id'].'');
	$requete=$bdd->prepare('INSERT INTO journalmoney (send, receive, amount, day,type) VALUES 
	(:send, :receive, :amount, :day,:type)');
		$requete->execute(array(
			'send'=>$_GET['send'],
			'receive'=>$_GET['id'],
			'amount'=>$_GET['amount'].' '.$_GET['devise'],
			'day'=>time(),
			'type'=>$_GET['operation']
		));
	$retour['message']="Demande envoyer avec succès";
	if($_GET['operation']=='recharge' || $_GET['operation']=='retrait'){
		$message=$_GET['send']." demande un(e) ".$_GET['operation']." de ".$_GET['amount'].' '.$_GET['devise'];
		require 'sms/index.php';
	}
}
?>