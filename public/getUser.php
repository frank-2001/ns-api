<?php
try { 
	$bdd;
if(isset($_GET['newUser'])){
		// Arrange le numero de l'utilisateur
	if (substr($_GET['telephone'],0,1)==0) {
		$numero=substr($_GET['telephone'],1);
	 	$_GET['telephone']='243'.$numero;
	}
	if (substr($_GET['telephone'],0,1)==9) {
	 $_GET['telephone']='243'.$_GET['telephone'];
	}
	if (substr($_GET['telephone'],0,1)==8) {
	 $_GET['telephone']='243'.$_GET['telephone'];
	}
	if (substr($_GET['telephone'],0,1)=='+') {
		$_GET['telephone']=substr($_GET['telephone'],1);
	}
	// Verification de l'existance de l'user
	$reponse = $bdd->query('SELECT * FROM users where telephones=\'' . $_GET['telephone'] .'\'');
	$nb=count($reponse->fetchAll());
	if ($nb > 0) {
		$retour["state"]=false;
		$retour["message"]="<strong>".$_GET['telephone']."</strong> Ce numero de telephone existe deja ";
	}
	else{
	//Enregistrement du compte
	$reponse=$bdd->prepare ('INSERT INTO users (names,telephones,jour,town,password,profil,actif) values (:names,:telephones,:jour,:town,:password,:profil,:actif)');
	$reponse->execute(array(
		'names'=>$_GET['names'],
	  	'telephones' =>$_GET['telephone'],
		'jour'=>time(),
		'town' =>$_GET['town'], 
		'password'=>md5($_GET['password']),
		'profil'=>"profilDefault.png",
		'actif'=>time(),
	));
	//Recuperation de l'id
	$pass=md5($_GET['password']);
	$requete= $bdd->prepare("SELECT*FROM users where telephones=:phone and password=:pass");
	$requete->bindParam(':phone',$_GET['telephone']);
	$requete->bindParam(':pass',$pass);
	$requete->execute();
	$resultats=$requete->fetchAll();
	//Mise en jour du token et du solde
	$key=openssl_encrypt($resultats[0]['id'],"AES-128-ECB",'nunua-store');//Creation key de cryptage 
	$amount=openssl_encrypt(0,"AES-128-ECB",$key);//Creation key de cryptage 
	$requete = $bdd->prepare('UPDATE users SET walletdol= :walletdol,walletFc= :walletFc,token=:token where id=\''.$resultats[0]['id'].'\'');
	$requete->execute(array(
				'walletdol' =>$amount,
				'walletFc' =>$amount,
				'token' =>$amount,
		));	
	$resultats=getData('users',['id',$resultats[0]['id']],$bdd);
	$retour["message"]="Felicitation ".$_GET['names']." votre compte a ete cree avec succes";
	$retour["state"]=true;
	$resultats[0]['password']=$pass;
	$resultats[0]['walletdol']=openssl_decrypt($resultats[0]['walletdol'],"AES-128-ECB",$key);
	$resultats[0]['walletFc']=openssl_decrypt($resultats[0]['walletFc'],"AES-128-ECB",$key);
	$resultats[0]['profil']=explode(',',$resultats[0]['profil']);
	$retour["resultat"]=$resultats;	
	
	}
}
else{
	$requete= $bdd->prepare("SELECT id,names,telephones,profil,town,actif FROM users order by actif DESC");
	$requete->execute();
	$resultats=$requete->fetchAll();
	$retour["state"]=true;
	$retour["message"]="Tout des utilisateurs disponible dans la base de donnees";
	$retour["nb"]=count($resultats);
	for ($i=0; $i < count($resultats); $i++) { 
		if ($resultats[$i]['actif']+60*2>time()) {
			$resultats[$i]['actif']="Actif";			
		}else{
			$resultats[$i]['actif']=timeDo($resultats[$i]['actif']+60*2);			
		}
		$resultats[$i]['profil']=explode(',',$resultats[$i]['profil']);
	}
	$retour["resultat"]=$resultats;
	}
}
catch(Exception $e){
	$retour["state"]=false;
	$retour["message"]="Erreur de connexion a la base de donnees";
}
// print_r($retour["resultat"])
?>