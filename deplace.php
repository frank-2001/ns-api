<?php
require 'bdd.php';
require 'functions.php';
$user=getData('users',['1','1'],$bdd);
$numbers=[];
for ($i=0;$i<count($user);$i++){
  array_push($numbers,$user[$i]['telephones']);
}
for ($i=0;$i<count($user);$i++){
	echo $i.'. '.$user[$i]['names'].' - '.$user[$i]['telephones'].'<br>';
	// array_push($numbers,$user[$i]['telephones']);
  }
// print_r($numbers);

?>