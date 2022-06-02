<?php
// Enregistre un abonnement a un magasin
try { 
	// Selection de la colonne de l'user grace a som id
		$requete= $bdd->prepare('SELECT * FROM datausers where id_user=:id');	
		$requete->bindParam(':id',$_GET['id_user']);
		$requete->execute();
		$nbUser=count($requete->fetchAll());
		// verification de sa presence
		// S'il n'existe pas on l'ajoute
		if ($nbUser==0) {
		$heart=array($_GET['id_mag']);
		$requete=$bdd->prepare ('INSERT INTO datausers (id_user,sub) values (:id_user,:sub)');
		$requete->execute(array(
			'id_user'=>$_GET['id_user'],
			'sub'=>implode(',', $heart),
		)); 
		$exist=false;
		}
// Si non on met a jour sa colone
		else{
		$requete= $bdd->prepare('SELECT * FROM datausers where id_user=:id');	
		$requete->bindParam(':id',$_GET['id_user']);
		$requete->execute();
			while($donnees=$requete->fetch()){
				$heartBase=$donnees['sub'];
				$heart=explode(',', $donnees['sub']); //Tableau des magasins heart
			 	$heartNb=count($heart); //nombre des magasins heart
			}
		$exist=false; //verificateur des id articles hearts for ne pas heart deux fois the even article
		for ($i=0; $i <$heartNb ; $i++) { 
			if ($heart[$i]==$_GET['id_mag']) {
				$exist=true;
			}
		}
		// Si l'article n'as jamais ete heart ajout de son id
		if ($exist==false) {
		 $newTable= $heartBase.','.$_GET['id_mag'];//ajout de l'Id 
		$requete = $bdd->prepare('UPDATE datausers SET sub= :sub where id_user=\''.$_GET['id_user'].'\'');
		$requete->execute(array(
			'sub' =>$newTable
		));					
		}
		}
	// Mis en jour de nombre des heart sur l'article
		$requete= $bdd->prepare('SELECT * FROM magasin where id=:id');	
		$requete->bindParam(':id',$_GET['id_mag']);
		$requete->execute();
			while($donnees=$requete->fetch()){
				$heartArticle=$donnees['heart']; //Tableau des produits heart
			}
		if ($exist==false) {
		$requete = $bdd->prepare('UPDATE magasin SET heart= :heart where id=\''.$_GET['id_mag'].'\'');
		$requete->execute(array(
			'heart' =>$heartArticle+1,
		));	
		
		$retour["heart"]=$heartArticle+1;
		$retour['state']=true;
		$retour["message"]="Ajouter avec succes";
	// Enregistre Notification
		$requete= $bdd->prepare('SELECT * FROM users where id=:id');
		$user=getData('users',['id',$_GET['id_user']],$bdd)[0]['names'].','.$_GET['id_user'];	
			$tUser=array($user,$_GET['id_user']);
		$requete=$bdd->prepare ('INSERT INTO notification (id,type,user,day,creator,state,title) values (:id,:type,:user,:day,:creator,:state,:title)');
		$requete->execute(array(
			'id'=>$_GET['id_mag'],
			'type'=>"Subscribe",
			'user'=>$user,
			'day' =>time(),
			'creator'=>$_GET['creator'],
			'state'=>'0',
			'title'=>$_GET['title'],

		));
		}
		else{
			$retour["heart"]=$heartArticle;
			$retour['state']=false;
			$retour["message"]="Echec vous etes deja aboner";

		}
	}
catch(Exception $e){
	$retour["state"]=false;
	$retour["message"]="Erreur de connexion a la base de donnees";
}
?>