<?php
try { 
	$bdd;
	if(isset($_GET['newComment'])){
		$requete=$bdd->prepare ('INSERT INTO comment (id_article,name_user,comment,day,id_user) values (:id_article,:name_user,:comment,:day,:id_user)');
		$requete->execute(array(
			'id_article'=>$_GET['id_article'],
		  	'name_user' => $_GET['name_user'],
			'comment'=>$_GET['comment'],
			'day' =>time(), 
			'id_user'=>$_GET['id_user'],
		)); 
		$requete= $bdd->prepare('SELECT * FROM article where id=:id');	
		$requete->bindParam(':id',$_GET['id_article']);
		$requete->execute();
			while($donnees=$requete->fetch()){
			 $comment=$donnees['comment'];
			}	
		$requete = $bdd->prepare('UPDATE article SET comment= :comment where id=\''.$_GET['id_article'].'\'');
		$requete->bindParam(':id',$_GET['id_article']);
	    $requete->execute(array(
	    'comment' =>$comment+1,));
		$retour["message"]="Commentaire envoyer avec succes";
		}
		else{
		$id=$_GET['comment'];
		updateData2('article',['views',intval($_GET['nb'])+1],['id',$id],$bdd);
		$requete= $bdd->prepare("SELECT * FROM comment where id_article=:id order by id DESC");		
		$requete->bindParam(':id',$_GET['comment']);
		$requete->execute();
		$resultats=$requete->fetchAll();
		$retour["state"]=true;
		$retour["message"]="Commentaire sur un article";
		$retour["number"]=count($resultats);
		$retour["resultat"]=$resultats;
		$i=0;
		while ($i<count($retour["resultat"])) {
		$retour["resultat"][$i]['day']=timeDo($retour["resultat"][$i]['day']);
		$i=$i+1;
		}
		}
	}
	catch(Exception $e){
		$retour["state"]=false;
		$retour["message"]="Erreur de connexion a la base de donnees";
	}
?>