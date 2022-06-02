<?php 
// if (token($_GET['id'],$_GET['token'],$bdd)['state']==1) {

if (isset($_GET['idEvent'])){
	$data=getData('placesevenement',['idEvent',$_GET['idEvent']],$bdd);
	$format = "d M Y à H:i";
	for ($i=0; $i < count($data); $i++) { 
		$data[$i]['evDay']=date($format,$data[$i]['evDay']);
		$data[$i]['idUser']=explode(',',$data[$i]['idUser']);
	}
	$retour['resultat']=$data;
}else{
	$data=getData('placesevenement',['idUser',$_GET['id']],$bdd);
	$format = "d M Y à H:i";
	for ($i=0; $i < count($data); $i++) { 
		$data[$i]['evDay']=date($format,$data[$i]['evDay']);
	}
	$retour['resultat']=$data;
}
// }
// else{
// 	$retour['message']="Token invalide";
// }
?>