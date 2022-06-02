<?php 
require 'bdd.php';
$requete= $bdd->prepare('SELECT * FROM livraison');	
$requete->execute();
$allCity=$requete->fetchAll();
if($_GET['code']=='Code@2001')
{
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Nunua-store</title>
</head>
<body>
<a href="?ok">Actualiser</a>
<a href="?newCity"><button>Nouvelle Ville</button></a>
<a href="?delAll"><button>Vider</button></a>
<?php if (isset($_GET['newCity'])) { ?>
<div>
<h2>Nouvelle ville</h2>
<form method="GET" action="#">
	<input type="text" name="ville" placeholder="ville">
	<input type="text" name="commune" placeholder="ex: Beu,Mulekera,...">
	<input type="text" name="quartier" placeholder="ex: Kasabi,Kasanga|malepe...">
	<button type="submit" name="saveCity">Enregistrer</button>
</form>
</div>
<?php } ?>
<br><br><br><br>
</body>
</html>
<?php
if (isset($_GET['saveCity'])) {
				$exist=false;
	$requete= $bdd->prepare('SELECT * FROM livraison');	
	$requete->execute();
			while($donnees=$requete->fetch()){
		if (ucwords($donnees['Ville'])==ucwords($_GET['ville'])) {
			$exist=true;
		}
			}
			if ($exist==false) {
			$req=$bdd->prepare ('INSERT INTO livraison (Ville,Commune,Quartier) values (:Ville,:Commune,:Quartier)');
				$req->execute(array(
					'Ville'=>$_GET['ville'],
					'Commune'=>$_GET['commune'],
					'Quartier'=>$_GET['quartier'],
				)); 
			}
			else{
				echo 'Cette ville existe deja<br>';
			}
?>
<script type="text/javascript">
// location.reload();
</script>
<?php
} 
if (isset($_GET['delAll'])) {
	$requete=$bdd->exec('TRUNCATE livraison');
} 
for ($i=0; $i <count($allCity) ; $i++) { 
$allCity[$i]['Commune']=explode(',',$allCity[$i]['Commune']);
$allCity[$i]['Quartier']=explode('|',$allCity[$i]['Quartier']);
for ($on=0; $on < count($allCity[$i]['Quartier']); $on++) { 
$allCity[$i]['Quartier'][$on]=explode(',',$allCity[$i]['Quartier'][$on]);
}
}
print_r($allCity);
}
?>