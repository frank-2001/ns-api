<?php
$id=$_GET['id'];
$cmd=getDataById($id,'commande',['id','Desc'],$bdd)[0];
$custum=getData('users',['id',explode(',', $cmd['custum'])[1]],$bdd);
$creator=getData('users',['id',explode(',', $cmd['creator'])[1]],$bdd);
if($cmd['state']==0 && $cmd['token']==$_GET['token'] ){
if (isset($_GET['confirmCmd'])) {
		$idArt=explode(',', $cmd['article'])[0];
		$infos=getDataById($idArt,'article',['id','Desc'],$bdd)[0];
		$qteIni=$infos['quantite'];
		if($qteIni>=explode(',', $cmd['article'])[3]){

		//Mis en jour de nombre d'article
		$requete = $bdd->prepare('UPDATE article SET quantite= :quantite where id=\''.$idArt.'\'');
			$requete->execute(array(
						'quantite' =>$qteIni-explode(',', $cmd['article'])[3]
			));
		$requete = $bdd->prepare('UPDATE commande SET state= :state where id=\''.$_GET['id'].'\'');
		$requete->execute(array(
			'state' =>1,
		));
		//Transfert de l'argent du compte 000 a au vendeur
		
		$retour=transMoney(1,$creator[0]['telephones'],explode(',', $cmd['article'])[2]*explode(',',$cmd['article'])[3],explode(',', $cmd['article'])[4],$bdd);
		$retour['state']=true;	
		}else{
			$retour['message']="Stock du vendeur insufisant";
			$retour['state']=false;
		}
		}
		if (isset($_GET['confirmCmdError'])) {
			//Transfert de l'argent du compte 000 a au 
			// transMoney($idSend,$phoneReceive,$amount,$devise,$bdd);
			$retour=transMoney(1,$custum[0]['telephones'],explode(',', $cmd['article'])[2]*explode(',',$cmd['article'])[3],explode(',', $cmd['article'])[4],$bdd);
			$requete = $bdd->prepare('UPDATE commande SET state= :state where id=\''.$_GET['id'].'\'');
				$requete->execute(array(
					'state' =>-1,
					));
			$retour['message']="Commande annulee avec succes";
			$retour['state']=true;	
		}
	}else{
			$retour['message']="Operation Invalide";
			$retour['state']=false;		
	}
?>