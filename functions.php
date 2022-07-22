<?php
function timeDo($stamp){// Retour le temps qu'un article a fait depuis sa publication
			$tm=time();
			if (intval($stamp)>intval($tm)) {
				$trueTm=intval($stamp)-intval($tm);
				$text="+";
			}else{
				$trueTm=intval($tm)-intval($stamp);
				$text="";
			}
			if ($trueTm>=31104000) {
				$time=$trueTm/31104000;
				$time=intval($time).' ans';
			}
			elseif($trueTm>=2592000){
				$time=$trueTm/2592000;
				$time=intval($time).' mois';
			}
			elseif($trueTm>=604800){
				$time=$trueTm/604800;
				$time=intval($time).' semaines';
			}
			elseif($trueTm>=86400){
				$time=$trueTm/86400;
				$time=intval($time).' jours';
			}
			elseif($trueTm>=3600){
				$time=$trueTm/3600;
				$time=intval($time).' h';
			}
			elseif($trueTm>=60){
				$time=$trueTm/60;
				$time=intval($time).' min';
			}
			elseif($trueTm<60 && $trueTm>0){
				$time=intval($trueTm).' sec';
			}
			else{
				$time="Now";
			}
			return $text.' '.$time;
	}
function token($id,$token,$bdd){
	$requete = $bdd->query('SELECT * FROM users where id=\'' . $id .'\'');
	$requete->execute();
	$user=$requete->fetchAll();
	if (count($user)==1) {
	$token=str_replace(' ', '+', $token); 
	if ($token==$user[0]['token']){
		//Creation new Token
			$keySender=openssl_encrypt($id, "AES-128-ECB" ,'nunua-store');
			$newToken=rand(0,1000000000);
			$newToken=openssl_encrypt($newToken, "AES-128-ECB" ,$keySender);
		//Update token
			$requete = $bdd->prepare('UPDATE users SET token=:token where id=\''.$id.'\'');
			$requete->execute(array(
				'token'=>$newToken));
		$out['state']=1;
		$out['message']="Token valide";
	}else{
		$out['state']=0;
		$out['message']="Token invalide";
	}
	}
	else{
	$out['state']=0;
	$out['message']="L'utilisateur n'existe pas";
	}
	return $out;
	}
function transMoney($idSend,$phoneReceive,$amount,$devise,$bdd){
	//Data Sender
	$requete = $bdd->query('SELECT * FROM users where id=\'' . $idSend .'\'');
	$requete->execute();
	$sender=$requete->fetchAll();
	//Data receiver
	$requete = $bdd->query('SELECT * FROM users where telephones=\'' . $phoneReceive .'\'');
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
	if($devise=='Fc') {
		$senderDol=openssl_decrypt($sender[0]['walletFc'], "AES-128-ECB" ,$keySender);
		$receiverDol=openssl_decrypt($receiver[0]['walletFc'], "AES-128-ECB" ,$keyReceiver);
	}
	elseif ($devise=='$') {
		$senderDol=openssl_decrypt($sender[0]['walletdol'], "AES-128-ECB" ,$keySender);
		$receiverDol=openssl_decrypt($receiver[0]['walletdol'], "AES-128-ECB" ,$keyReceiver);
	}
	else{
		$out['state']=false;
		$out['message']="Devise non connue";
	}
	if($amount<=$senderDol) {
	//Sender Update wallet 
		$senderDol=doubleval($senderDol)-doubleval($amount);
		$senderDol=openssl_encrypt($senderDol, "AES-128-ECB" ,$keySender);
	//Receiver Update wallet
		$receiverDol=doubleval($receiverDol)+doubleval($amount);
		$receiverDol=openssl_encrypt($receiverDol, "AES-128-ECB" ,$keyReceiver);
	//Crediter le compte apres detection du devise	
		if ($devise=='$') {
	//Sender Update
	updateDataByid('users','walletdol',array('id',$sender[0]['id']),$senderDol,$bdd);
	//Receiver Update
	updateDataByid('users','walletdol',array('id',$receiver[0]['id']),$receiverDol,$bdd);					
		}
		if($devise=='Fc'){
		//Sender Update
	updateDataByid('users','walletFc',array('id',$sender[0]['id']),$senderDol,$bdd);
	//Receiver Update
	updateDataByid('users','walletFc',array('id',$receiver[0]['id']),$receiverDol,$bdd);	
	}
		$dataCorrect=true;
	}
	else{
		$out['message']="Montant sortie superier au solde";
	}
	//Journal des transfers enregistrements		
	if ($dataCorrect==true) {
	$requete=$bdd->prepare('INSERT INTO journalmoney (send, receive, amount, day,type,state) VALUES 
	(:send, :receive, :amount, :day,:type,:state)');
		$requete->execute(array(
			'send'=>$sender[0]['id'],
			'receive'=>$receiver[0]['id'],
			'amount'=>$amount.' '.$devise,
			'day'=>time(),
			'type'=>'transfert',
			'state'=>'1',
		));	
	$requete=$bdd->prepare ('INSERT INTO notification (id,type,user,day,creator,state,title) values (:id,:type,:user,:day,:creator,:state,:title)');
		$requete->execute(array(
			'id'=>0,
			'type'=>"money",
			'user'=>$sender[0]['id'].','.$sender[0]['names'],
			'day' =>time(),
			'creator'=>$receiver[0]['id'],
			'state'=>'0',
			'title'=>'Vous avez recu '.$amount.' '.$devise,
		));
		$out['message']="Transfert effectue avec succes";
		$out['state']=true;
	}
	}
	else{
	$out['state']=false;
	$out['message']="L'utilisateur n'existe pas";
	}
	return $out;
	}
function getDataById($id,$table,$order,$bdd){ //Avoir les donnees d'une table grace a l'id
	if ($id==0) {
			$requete = $bdd->query('SELECT * FROM '. $table .' order by '. $order[0] .' '.$order[1].'');//Recuperation des information du compte
			$requete->execute();
			$out=$requete->fetchAll();
	}
	else{
	$requete = $bdd->query('SELECT * FROM '. $table .' where '.$order[0].'=\'' . $id .'\'');//Recuperation des information du compte
	$requete->execute();
	$out=$requete->fetchAll();		
	}
	return $out;
	}
function updateDataByid($table,$colone,$select,$data,$bdd){
	if ($select==0) {
	$requete = $bdd->prepare('UPDATE '.$table.' SET '.$colone.'=:colone');
	$requete->execute(array(
		'colone'=>$data));	
	}else{
	$requete = $bdd->prepare('UPDATE '.$table.' SET '.$colone.'=:colone where '.$select[0].'=\''.$select[1].'\'');
	$requete->execute(array(
		'colone'=>$data));	
	}}
function updateData2($table,$colonne,$filter,$bdd){
	$sql="UPDATE $table SET $colonne[0]='$colonne[1]' WHERE $filter[0]=$filter[1]";
	$bdd->exec($sql);
	}
function deleteData($table,$select,$bdd){
	$requete=$bdd->exec('DELETE FROM '.$table.' where '.$select[0].'='.$select[1].'');
}
function renameMoveImages($images,$destination,$idAuteur)
{
$imgs=explode(',',$images);
for ($i=0; $i < count($imgs); $i++) { 
$ext[$i]=explode('.',$imgs[$i]);
$nameImage[$i]='Nunua-store'.$i.''.$idAuteur.''.time().'.'.$ext[$i][1];
rename('fichier/waitingImages/'.$imgs[$i],$destination.''.$nameImage[$i]);
}
return implode(',', $nameImage);
}
function getData($table,$param,$bdd){ //Avoir les donnees d'une table grace a l'id
			$requete = $bdd->query('SELECT * FROM '.$table.' where '.$param[0].'='.$param[1]);//Recuperation des information du compte
			$requete->execute();
			$out=$requete->fetchAll();
	return $out;
	}
//Decryptage	
function decrypt($data){
	$encoded = $data;   // <-- encoded string from the request
	$decoded = "";
	$encoded = explode('o', $encoded);
	// print_r($encoded);
	for( $i = 0; $i < count($encoded)-1; $i++ ) {
	    $a = intval($encoded[$i])/375;
	    $encoded[$i]=chr($a);
	    $decoded=$decoded.''.$encoded[$i];
	}
	return $decoded;
}

?>