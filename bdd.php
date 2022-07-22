<?php 
#ce sccript connecte le site la basse des donnees

if ($_SERVER['SERVER_NAME']=="localhost") {
	#Base des donnes local
	$host='localhost';
	$dbname='nsdb';
	$user='root';
	$pass='';
}else{
	#Base des donnees en ligne
	$host='sql.freedb.tech';
	$dbname='freedb_nstore-db';
	$user='freedb_franky';
	$pass='b2pXgQgE*cQgG6d';
	// $bdd = new PDO('mysql:host=sql.freedb.tech;dbname=freedb_nstore-db', 'freedb_franky', 'b2pXgQgE*cQgG6d');
}
try { 
	$bdd = 	new PDO('mysql:host='.$host.';dbname='.$dbname, $user, $pass); 
	//Save backup db every 24 hours
	$lastBack=file_get_contents("lastBack.txt");
	if (intval(time())-intval($lastBack)>1 && $_SERVER['SERVER_NAME']!="localhost") {
		require 'exportDb.php';
		backupDb($host,$user,$pass,$dbname,$tables = '*');
		$myfile = fopen("lastBack.txt", "w") or die("Unable to open file!");
		$txt = time();
		fwrite($myfile, $txt);
		fclose($myfile);
	}
}
catch   (PDOException $pe)
    {
    die ("I cannot connect to the database " . $pe->getMessage());
}
?>