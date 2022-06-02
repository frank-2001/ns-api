<?php
// Evenements
// print_r(getData('evenement',['1','1'],$bdd));
$order = array('evDay','ASC');
$result=getDataById('0','evenement',$order,$bdd);
	//Give form to same data
	$format = "d M Y à H:i";
	for ($i=0; $i <count($result) ; $i++) {
		$deleteEventDate=intval($result[$i]['evDay'])+3600*12;
		if(intval(time())>=intval($deleteEventDate)){//Update state of a old event
		$select=array('id',$result[$i]['id']);
		updateDataByid('evenement','state',$select,'1',$bdd);
		$select=array('idEvent',$result[$i]['id']);
		deleteData('placesevenement',$select,$bdd);
		}
		$result[$i]['evDay']=date($format,$result[$i]['evDay']);
		$result[$i]['creation']=timeDo($result[$i]['creation']);
		$result[$i]['nbInvit']=explode('/',$result[$i]['nbInvit']);
		$result[$i]['prix']=explode(',',$result[$i]['prix']);
		$result[$i]['images']=explode(',',$result[$i]['images']);
		$result[$i]['auteur']=explode(',',$result[$i]['auteur']);
	}
	$retour['resultat']=$result;
// print_r($retour['resultat']);
?>
