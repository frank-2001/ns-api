<?php
$d=explode('-', $_GET['day']);
$h=explode(':', $d[3]);
$stamp=mktime($h[0],$h[1],0,$d[1],$d[2],$d[0]);
if($stamp>intval(time())){
$imgs=explode(',',$_GET['images']);
$auteur=explode(',',$_GET['auteur']);
// $tokenCheck=token($auteur[1],$_GET['token'],$bdd);//Resustat verification de token
// if($tokenCheck['state']==1){
for ($i=0; $i < count($imgs); $i++) { 
$ext[$i]=explode('.',$imgs[$i]);
$nameImage[$i]='Nunua-store'.$i.''.$auteur[1].''.time().'.'.$ext[$i][1];
rename('fichier/waitingImages/'.$imgs[$i],'fichier/eventPictures/'.$nameImage[$i]);
}
// $day=date('d m Y a H:i:s',$stamp);
// echo $day;
$reponse=$bdd->prepare('INSERT INTO evenement (creation,title,town,adress,description,images,auteur,nbInvit,prix,magasin,evDay) values (:creation,:title,:town,:adress,:description,:images,:auteur,:nbInvit,:prix,:magasin,:evDay)');
$reponse->execute(array(
	'creation'=>time(),
	'title'=>$_GET['title'],
	'town' =>$_GET['town'], 
	'adress'=>$_GET['adress'],
	'description'=>$_GET['description'],
	'images'=>implode(',',$nameImage),
	'auteur'=>$_GET['auteur'],
	'nbInvit'=>$_GET['place'].'/'.$_GET['place'],
	'prix'=>$_GET['prix'],
	'magasin'=>$_GET['magasin'],
	'evDay' =>$stamp,
));
	$retour['state']=true;
	$retour['message']='Evenement créé avec succes';	
}
else{
	// echo "Date Invalide";
	$retour['state']=false;
	$retour['message']='Date Invalide';	
}//
// }
// else{
// 	$retour['message']='Token invalide';
// }
?>