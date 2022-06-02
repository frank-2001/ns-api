<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Time</title>
</head>
<body>
<?php 
echo $d=date('h/i/s/m/d/Y').'<br>';
echo $date="2010-04-01".'<br>';
echo $tm=time().'<br>';
$dt=explode('/', $d);
$dt[2]=intval($dt[2]);
echo $sec=mktime(intval($dt[0]),intval($dt[1]),intval($dt[2]),intval($dt[3]),intval($dt[4]),intval($dt[5])).'<br>';

$trueTm=intval($tm)-intval($sec);
if ($trueTm>=31104000) {
	$time=$trueTm/31104000;
	$time=intval($time).' Ans';
}
elseif($trueTm>=2592000){
	$time=$trueTm/2592000;
	$time=intval($time).' Mois';
}
elseif($trueTm>=604800){
	$time=$trueTm/604800;
	$time=intval($time).' Semaines';
}
elseif($trueTm>=86400){
	$time=$trueTm/86400;
	$time=intval($time).' Jours';
}
elseif($trueTm>=3600){
	$time=$trueTm/3600;
	$time=intval($time).' Heurs';
}
elseif($trueTm>=60){
	$time=$trueTm/60;
	$time=intval($time).' Minutes';
}
elseif($trueTm<60 && $trueTm>0  ){
	$time=intval($trueTm).' Secondes';
}
else{
	$time="Now";
}
echo $time.'<br>';
// if ($min>=60) {
// 	$hour=$min/60;
// 	echo $hour.' heurs <br>';
// 	if ($hour>=24) {
// 		$jr=$hour/24;
// 		echo $jr.' jours <br>';
// 		if ($jr>=30) {
// 			$mois=$jr/30;
// 			echo $mois.' mois <br>';
// 			if ($mois>=12) {
// 				$year=$mois/12;
// 				echo $year.' ans ';
// 			}
// 		}
// 	}
// }

for ($i=0; $i <count($dt) ; $i++) { 
	echo $dt[$i].'<br>';
}
 ?>
</body>
</html>
1i = 60sec
1h = 3 600sec
1d = 86 400sec
1w = 604 800sec
1m = 2 592 000sec
1y = 31 104 000sec