<?php
// Enregistre un heart sur un article
try { 
	// Selection de la colonne de l'user grace a som id
		$requete= $bdd->prepare('SELECT * FROM datausers where id_user=:id');	
		$requete->bindParam(':id',$_GET['id_user']);
		$requete->execute();
		$nbUser=count($requete->fetchAll());
		// verification de sa presence
		// S'il n'existe pas on l'ajoute
		if ($nbUser==0) {
		$heart=array($_GET['id_article']);
		$requete=$bdd->prepare ('INSERT INTO datausers (id_user,heart) values (:id_user,:heart)');
		$requete->execute(array(
			'id_user'=>$_GET['id_user'],
			'heart'=>implode(',', $heart),
		)); 
		$exist=false;
		}
// Si non on met a jour sa colone
		else{
		$requete= $bdd->prepare('SELECT * FROM datausers where id_user=:id');	
		$requete->bindParam(':id',$_GET['id_user']);
		$requete->execute();
			while($donnees=$requete->fetch()){
				$heartBase=$donnees['heart'];
				$heart=explode(',', $donnees['heart']); //Tableau des produits heart
			 	$heartNb=count($heart); //nombre des articles heart
			}
		$exist=false; //verificateur des id articles hearts for ne pas heart deux fois the even article
		for ($i=0; $i <$heartNb ; $i++) { 
			if ($heart[$i]==$_GET['id_article']) {
				$exist=true;
			}
		}
		// Si l'article n'as jamais ete heart ajout de son id
		if ($exist==false) {
		 $newTable= $heartBase.','.$_GET['id_article'];//ajout de l'Id 
		$requete = $bdd->prepare('UPDATE datausers SET heart= :heart where id_user=\''.$_GET['id_user'].'\'');
		$requete->execute(array(
			'heart' =>$newTable
		));					
		}
		}
	// Mis en jour de nombre des heart sur l'article
		$requete= $bdd->prepare('SELECT * FROM article where id=:id');	
		$requete->bindParam(':id',$_GET['id_article']);
		$requete->execute();
			while($donnees=$requete->fetch()){
				$heartArticle=$donnees['heart']; //Tableau des produits heart
			}
		if ($exist==false) {
		$requete = $bdd->prepare('UPDATE article SET heart= :heart where id=\''.$_GET['id_article'].'\'');
		$requete->execute(array(
			'heart' =>$heartArticle+1,
		));	
		
		$retour["heart"]=$heartArticle+1;
		$retour['state']=true;
		$retour["message"]="Ajouter avec succes";
	// Enregistre Notification
		$requete= $bdd->prepare('SELECT * FROM users where id=:id');	
		$requete->bindParam(':id',$_GET['id_user']);
		$requete->execute();
			while($donnees=$requete->fetch()){
				$user=$donnees['names']; //name of user who like picture 
			}
			$tUser=array($user,$_GET['id_user']);
		$requete=$bdd->prepare ('INSERT INTO notification (id,type,user,day,creator,state,title) values (:id,:type,:user,:day,:creator,:state,:title)');
		$requete->execute(array(
			'id'=>$_GET['id_article'],
			'type'=>"heart",
			'user'=>implode(',', $tUser),
			'day' =>date('h/i/s/m/d/Y'),
			'creator'=>$_GET['creator'],
			'state'=>'0',
			'title'=>$_GET['title'],
		));
		}
		else{
			$retour["heart"]=$heartArticle;
			$retour['state']=false;
			$retour["message"]="Echec vous avez deja aimer cette photo";

		}
	}
catch(Exception $e){
	$retour["state"]=false;
	$retour["message"]="Erreur de connexion a la base de donnees";
}
?>