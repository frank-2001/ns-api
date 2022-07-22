<?php
try { 
	$bdd;
	$phone=$_GET['phone'];
	$pass=$_GET['pass'];
		// Arrange le numero de l'utilisateur
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
	$pass1=md5($pass);		
	// echo $pass1;
	$requete= $bdd->prepare("SELECT*FROM users where telephones=:phone and password=:pass");
	$requete->bindParam(':phone',$phone);
	$requete->bindParam(':pass',$pass1);
	$requete->execute();
	$resultats=$requete->fetchAll();
	$retour["nb"]=count($resultats);
	if ($retour["nb"]==0) {
		$retour["message"]="Mot de passe ou numero de telephone incorect";
	}
	else{ 
	$retour["message"]="connecter avec succes";
	$retour["state"]=true;
	$resultats[0]['password']=$pass;
	$key=openssl_encrypt($resultats[0]['id'],"AES-128-ECB",'nunua-store');
	$resultats[0]['walletdol']=openssl_decrypt($resultats[0]['walletdol'],"AES-128-ECB",$key);
	$resultats[0]['walletFc']=openssl_decrypt($resultats[0]['walletFc'],"AES-128-ECB",$key);
	$resultats[0]['profil']=explode(',',$resultats[0]['profil']);
	$retour["resultat"]=$resultats;	
}
}
catch(Exception $e){
	$retour["state"]=false;
	$retour["message"]="Erreur de connexion a la base de donnees";
}
?>