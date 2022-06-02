<?php
// if (token($_GET['id'],$_GET['token'],$bdd)['state']==1) {
$destination='fichier/profilUser/';
$ext=explode('.',$_GET['image'])[1];
$nameImage='profil-nunua-store'.$_GET['id'].''.time().'.'.$ext;
rename('fichier/waitingImages/'.$_GET['image'],$destination.''.$nameImage);
//Recuperer d'autres images de profil
$user=getDataById($_GET['id'],'users',array('id',''),$bdd);
$olderPic=$user[0]['profil'];
$newPic=$nameImage.','.$olderPic;
updateDataByid('users','profil',array('id',$_GET['id']),$newPic,$bdd);
$retour['message']="profil update avec succes";
// }
// else{
// 	$retour['message']="Token invalide";
// }
?>