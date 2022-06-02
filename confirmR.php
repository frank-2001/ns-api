<?php 
require 'bdd.php';
require 'functions.php';
$pass=getData('admin',['1','1'],$bdd);
$connect=1;
if (isset($_POST['id'])) {
	$pass=getData('admin',['1',$_POST['id']],$bdd);
	if(count($pass)==1 AND $pass[0]['pwd']==md5($_POST['pass'])){
		$connect=0;
		echo "<strong style='background:blue;color:white;padding:1%'>Bienvenu <strong>".$pass[0]['name']."</strong></strong> <a href='confirmR.php'><button style='background:red;color:white;padding:1%'>Deconnexion</button></a>";	
		$recharge=getData('journalmoney',['state','0'],$bdd);

	}else{
		echo "<strong style='background:red;color:white;padding:1%'>Identifiant non retrouver</strong> ";
	}
}
if (isset($_GET['confirm'])) {
	$pass=getData('admin',['1',$_GET['id']],$bdd);
	if ($pass[0]['pwd']==$_GET['pass']) {
		$oneR=getData('journalmoney',['id',$_GET['recharge']],$bdd)[0];
		// print_r(	$oneR);
		if ($oneR['state']==0) {
		$_GET['id']=$oneR['receive'];
		$_GET['devise']=explode(' ', $oneR['amount'])[1];
		$_GET['amount']=explode(' ', $oneR['amount'])[0];
		$_GET['idjournal']=$_GET['recharge'];

		require 'confirmRecharge.php';
	}else{
		echo 'Operation deja executer';
	}
	}else{
	echo "<strong style='background:red;color:white;padding:1%'>Identifiant non valable</strong> ";
		}
}
if (isset($_GET['cencel'])) {
	$pass=getData('admin',['1',$_GET['id']],$bdd);
	if ($pass[0]['pwd']==$_GET['pass']) {
		$oneR=getData('journalmoney',['id',$_GET['recharge']],$bdd)[0];
		if ($oneR['state']==0) {
			$requete = $bdd->prepare('UPDATE journalmoney SET state=:state where id=\''.$oneR['id'].'\'');
			$requete->execute(array(
			'state'=>-1));
			echo 'Annulation faite avec succes';
	}else{
				echo 'Operation deja executer';
	}
	}else{
	echo "<strong style='background:red;color:white;padding:1%'>Identifiant non valable</strong> ";
		}
}
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Nunua-store</title>
</head>
<style type="text/css">
	body{
		background: darkcyan;
		margin: 2%;
		color: black;
		border: 2px solid whitesmoke;
		padding: 1%;
	}
	.form{
		text-align: center;
	}
</style>
<body>
<h2 style="text-align: center;">Nunua-store Admin</h2>
<?php if($connect==1){?>
<form method="POST" action="confirmR.php?req" class="form">
	<input type="number" name="id" placeholder="Identifiant" required>
	<input type="password" name="pass" placeholder="Mot de passe admin" required>
	<button>Valider</button>
</form>
<?php }else{
for ($i=0; $i < count($recharge); $i++) { 
	if ($recharge[$i]['type']=='recharge') {
	echo getData('users',['id',$recharge[$i]['receive']],$bdd)[0]['names'].'        '.$recharge[$i]['amount'].' <a href="?confirm&id='.$_POST['id'].'&pass='.md5($_POST['pass']).'&recharge='.$recharge[$i]['id'].'"><button>Confirmer</button></a>  <a href="?cencel&id='.$_POST['id'].'&pass='.md5($_POST['pass']).'&recharge='.$recharge[$i]['id'].'"><button>Annuler</button></a><br>'; 
	}
} } ?>
</body>
</html>