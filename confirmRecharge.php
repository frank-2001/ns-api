<?php
//echo '-Id=>'.$_GET['id'];
$retour['state']=false;
$key=openssl_encrypt($_GET['id'],"AES-128-ECB",'nunua-store');//Creation key de cryptage 
	$requete = $bdd->query('SELECT * FROM users where id=\'' . $_GET['id'] .'\'');//Recuperation des information du compte
	$requete->execute();
		while($donnees=$requete->fetch())
		{
			$names=$donnees['names'];//Nom account
			$amountFc=$donnees['walletFc'];//Solde en Fc
			$amountDol=$donnees['walletdol'];//Solde en Dollar
			// //echo $amountFc;
		}
// print_r($donnees);
if($_GET['devise']=='Fc'){//Identification de la devise
	if($amountFc=='0' OR $amountFc==' '){//Si le solde est vide
		$amountFc=openssl_encrypt($_GET['amount'], "AES-128-ECB" ,$key);//Cryptage du montant
		$requete = $bdd->prepare('UPDATE users SET walletFc= :walletFc where id=\''.$_GET['id'].'\'');//Update du compte
			$requete->execute(array(
					'walletFc' =>$amountFc
			));	
	$retour['state']=true;
	$retour['message']="Transfert effectué avec succes";
	}
else{//si compte n'est pas vide
	$amountFc=openssl_decrypt($amountFc, "AES-128-ECB" ,$key);//Decrypte montant
	$amountFc=doubleval($amountFc)+doubleval($_GET['amount']);//Ajouter la somme
	$amountFc=openssl_encrypt($amountFc, "AES-128-ECB" ,$key);//Cryptage du nouveau montant
	$requete = $bdd->prepare('UPDATE users SET walletFc= :walletFc where id=\''.$_GET['id'].'\'');//Update
	$requete->execute(array(
			'walletFc' =>$amountFc
	));
	$retour['state']=true;
	$retour['message']="Transfert effectué avec succes";
}
}
if($_GET['devise']=='$'){
	if($amountDol=='0' OR $amountDol==' '){
	$amountDol=openssl_encrypt($_GET['amount'], "AES-128-ECB" ,$key);//Cryptage du montant
		$requete = $bdd->prepare('UPDATE users SET wallet$= :wallet$ where id=\''.$_GET['id'].'\'');//Update du compte
			$requete->execute(array(
					'wallet$' =>$amountDol
			));	
	$retour['state']=true;
	$retour['message']="Transfert effectué avec succes";
	}
		else{
	$amountDol=openssl_decrypt($amountDol, "AES-128-ECB" ,$key);
	$amountDol=doubleval($amountDol)+doubleval($_GET['amount']);
	$amountDol=openssl_encrypt($amountDol, "AES-128-ECB" ,$key);
	$requete = $bdd->prepare('UPDATE users SET walletdol= :walletdol where id=\''.$_GET['id'].'\'');
		$requete->execute(array(
				'walletdol' =>$amountDol
		));	
	}
	$retour['state']=true;
	$retour['message']="Transfert effectué avec succes";
}
if ($retour['state']==true) {
	$requete = $bdd->prepare('UPDATE journalmoney SET state=:state where id=\''.$_GET['idjournal'].'\'');
	$requete->execute(array(
			'state'=>1));
	// Notification
	$requete=$bdd->prepare ('INSERT INTO notification (id,type,user,day,creator,state,title) values (:id,:type,:user,:day,:creator,:state,:title)');
		$requete->execute(array(
			'id'=>0,
			'type'=>"money",
			'user'=>'1,Nunua-store',
			'day' =>time(),
			'creator'=>$_GET['id'],
			'state'=>'0',
			'title'=>'Vous avez recu '.$_GET['amount'].' '.$_GET['devise'],
		));
}
print_r($retour);
?>