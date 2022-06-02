<?php
if(token($_GET['idUser'],$_GET['token'],$bdd)['state']==1){ 
	// Get Event Data
	$order=array('id','Desc');
	$event=getDataById($_GET['idEvnt'],'evenement',$order,$bdd);
	$prixEvent=explode(',',$event[0]['prix']);
	// Get Custum Data 
	$order=array('id','Desc');
	$custom=getDataById($_GET['idUser'],'users',$order,$bdd);
	$key=openssl_encrypt($custom[0]['id'],"AES-128-ECB",'nunua-store').'  ';//Creation key de cryptage 
	if ($prixEvent[1]=='Fc') {
		$soldeCustom=openssl_decrypt($custom[0]['walletFc'],"AES-128-ECB",$key);
	}
	if ($prixEvent[1]=='$') {
		$soldeCustom=openssl_decrypt($custom[0]['walletdol'],"AES-128-ECB",$key);
	}
	// Get Buyer Data 
	$order=array('id','Desc');
	$buyer=getDataById(explode(',',$event[0]['auteur'])[1],'users',$order,$bdd);
	$buyerPhone=$buyer[0]['telephones'];
	//places of event
	$requete = $bdd->query('SELECT * FROM placesevenement where idEvent='.$_GET['idEvnt'].' AND idUser='.$_GET['idUser'].'');//Recuperation des information du compte
	$requete->execute();
	$places=$requete->fetchAll();
if (count($event)==1) {
	$nbInvit=explode('/',$event[0]['nbInvit']);
	if ($_GET['qteInvit']<=$nbInvit[0]) {
		$deleteEventDate=intval($event[0]['evDay'])+3600*12;
		if(intval(time())<intval($deleteEventDate)){
			if ($soldeCustom>=$prixEvent[0]) {
			// Mis en jour de nombre des billets de l'evenement
			$table="evenement";
			$colone="nbInvit";
			$select=array('id',$_GET['idEvnt']);
			$data=$nbInvit[0]-$_GET['qteInvit'].'/'.$nbInvit[1];
			updateDataByid($table,$colone,$select,$data,$bdd);
			// Creation d'un billet
			$placeCode=$_GET['idUser'].'-'.$_GET['idEvnt'].'-'.time().'-'.$_GET['qteInvit'];
			$placeCode=md5($placeCode);
			//Mis en jour de la place existantes
			if(count($places)!=0){
				$select=array('id',$places[0]['id']);
				$data=$places[0]['placeNumber']+$_GET['qteInvit'];
				updateDataByid('placesevenement','placeNumber',$select,$data,$bdd);			
			}
			else{//Creation d'une nouvelle place
			$requete=$bdd->prepare('INSERT INTO placesevenement (idEvent, evDay, idUser, placeCode,placeNumber, adress,titleInvit) VALUES 
		(:idEvent, :evDay, :idUser, :placeCode, :placeNumber, :adress,:titleInvit)');
			$requete->execute(array(
				'idEvent'=>$_GET['idEvnt'],
				'evDay'=>$event[0]['evDay'],
				'idUser'=>$_GET['idUser'].','.$custom[0]["names"],
				'placeCode'=>$placeCode,
				'placeNumber'=>$_GET['qteInvit'],
				'adress'=>$_GET['adress'],
				'titleInvit'=>$event[0]['title'],
			));
		}
		$amount=$prixEvent[0]*$_GET['qteInvit'];
		$devise=$prixEvent[1];
		transMoney($custom[0]['id'],$buyerPhone,$amount,$devise,$bdd);
		$retour['message']="Félicitation, l'achat de ".$_GET['qteInvit']." billets est éffectué avec succès";
		}else{
	$retour['message']="Votre solde est Insufisant veuillez recharger votre compte";					
		}
	}else{
	$retour['message']="Temps de l'évenement Expiré";					
	}
	}
	else{
	$retour['message']="Nombre des billets inferieur a votre demande";			
	}
}
else{
	$retour['message']="Evenement invalide";	
}
}else{
	$retour['message']="Token invalide";
}
?>