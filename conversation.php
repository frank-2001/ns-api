<?php
// echo date('H/i/s/m/d/Y');
// Traitement d'une conversation
// $_GET['contenu']='message:string,IdEmeteur,IdReveur,jour:date'; forme du message
if (isset($_GET['contenu'])) { //Envoi d'un message
$_GET['contenu']=$_GET['contenu'].','.date('H/i/s/m/d/Y');//Ajouter une date au message
$requete= $bdd->prepare('SELECT * FROM conversation where id0=:id0 AND id1=:id1 OR id0=:id1 AND id1=:id0');
		$requete->bindParam(':id0',$_GET['id0']);
		$requete->bindParam(':id1',$_GET['id1']);
		$requete->execute();
if (count($requete->fetchAll())>0) {
//Verification de l'existance de la conversation
		$requete= $bdd->prepare('SELECT * FROM conversation where id0=:id0 AND id1=:id1 OR id0=:id1 AND id1=:id0');
		$requete->bindParam(':id0',$_GET['id0']);
		$requete->bindParam(':id1',$_GET['id1']);
		$requete->execute();
		while($donnees=$requete->fetch()){
				$oldContenu=$donnees['contenu'];//Recuperation de Ancienne conversations
				$msgUnRead=$donnees['msgUnRead'];
		}
		$contenu=$oldContenu.'|'.$_GET['contenu'];//Nouvelle converstion mis en jour
		
		$requete = $bdd->prepare('UPDATE conversation SET contenu= :contenu,msgUnRead=:msgUnRead  where id0=\''.$_GET['id0'].'\' AND id1=\''.$_GET['id1'].'\' OR id0=\''.$_GET['id1'].'\' AND id1=\''.$_GET['id0'].'\'');
		$requete->execute(array(
			'contenu' =>$contenu,//Enregistrement de la nouvelle conversation
			'msgUnRead'=>$msgUnRead+1//Mise en jour des nombres des message non lus
		));	
		}
else{//Creation d'une nouvelle conversation
		$contenu=$_GET['contenu'];
		$requete=$bdd->prepare ('INSERT INTO conversation (id0,id1,nom0,nom1,contenu,msgUnRead) values (:id0,:id1,:nom0,:nom1,:contenu,:msgUnRead)');
				$requete->execute(array(
					'id0'=>$_GET['id0'],
					'id1'=>$_GET['id1'],
					'nom0'=>$_GET['nom0'],
					'nom1'=>$_GET['nom1'],
					'contenu'=>$contenu,
					'msgUnRead'=>1,
				));
}
}
if(isset($_GET['id1'])){//Recuperation d'une conversation

$requete= $bdd->prepare('SELECT * FROM conversation where id0=:id0 AND id1=:id1 OR id0=:id1 AND id1=:id0');
		$requete->bindParam(':id1',$_GET['id1']);
		$requete->bindParam(':id0',$_GET['id0']);
		$requete->execute();
		if (count($requete->fetchAll())>0) {
			$requete= $bdd->prepare('SELECT * FROM conversation where id0=:id0 AND id1=:id1 OR id0=:id1 AND id1=:id0');
			$requete->bindParam(':id1',$_GET['id1']);
			$requete->bindParam(':id0',$_GET['id0']);
			$requete->execute();
			while($donnees=$requete->fetch()){
				$contenu=explode('|', $donnees['contenu']); //Separation  des message dans un tableau
			}
			for ($i=0; $i <count($contenu) ; $i++) { 
				$contenu[$i]=explode(',', $contenu[$i]);//Separation des contenus d'un message dans un tableau
			}
for ($i=0; $i < count($contenu); $i++) { //Transformation de la data a la duree du message forme= 10 min...
		$jour=explode('/',$contenu[$i][3]);//Separation des elements d'une data dans un tableau
		$sec=mktime(intval($jour[0]),intval($jour[1]),intval($jour[2]),intval($jour[3]),intval($jour[4]),intval($jour[5]));//Notre des secondes ecoule depuis 1970 jusqu'a la date de l'envoi
		$contenu[$i][3]=timeDo(intval($sec));
	}
			// print_r($contenu);	

	$nbMsg=count($contenu)-1;
	if ($contenu[count($contenu)-1][2]==$_GET['id0'] AND isset($_GET['Read'])) {//Lire un message
		$requete = $bdd->prepare('UPDATE conversation SET msgUnRead=:msgUnRead  where id0=\''.$_GET['id0'].'\' AND id1=\''.$_GET['id1'].'\' OR id0=\''.$_GET['id1'].'\' AND id1=\''.$_GET['id0'].'\'');
		$requete->execute(array(
			'msgUnRead'=>0,//Mise en jour des nombres des message non lus
		));	
	}
}else{
	$contenu='';
}
		$retour['resultat']=$contenu;
}
else{//Recuperation de toute  les converations d'un utilisateur
		$requete= $bdd->prepare('SELECT * FROM conversation where id0=:id0 OR id1=:id0');
		$requete->bindParam(':id0',$_GET['id0']);
		$requete->execute();
	if (count($requete->fetchAll())>0) {
			$requete= $bdd->prepare('SELECT * FROM conversation where id0=:id0 OR id1=:id0');
			$requete->bindParam(':id0',$_GET['id0']);
			$requete->execute();
		$i=0;
			while($donnees=$requete->fetch()){
				if ($donnees['id0']==$_GET['id0']) {
					$nom=$donnees['nom1'];
					$id=$donnees['id1'];
				}else{
					$nom=$donnees['nom0'];
					$id=$donnees['id0'];
				}
				$info= array('nom' => $nom,'id'=>$id );//correspondant nom et id
				$contenu=explode('|', $donnees['contenu']); //messages
				$allData[$i]=array('infos'=>$info,'data'=>$contenu,'msgUnRead'=>$donnees['msgUnRead']);//tableau de la converation 
				$i++;
			}
			$nb1=count($allData);
			for ($in=0; $in <$nb1 ; $in++) {
				$nb=count($allData[$in]['data']);

				for ($i=0; $i <$nb; $i++) { 
					$allData[$in]['data'][$i]=explode(',', $allData[$in]['data'][$i]);

					$jour=explode('/',$allData[$in]['data'][$i][3]);
						$sec=mktime(intval($jour[0]),intval($jour[1]),intval($jour[2]),intval($jour[3]),intval($jour[4]),intval($jour[5]));
						$tm=time();
						$trueTm=intval($tm)-intval($sec);
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
						$allData[$in]['data'][$i][3]=$time;
				}
			// print_r($allData);

}
}else{
	$allData='';
}		
		$retour['resultat']=$allData;
}
?>