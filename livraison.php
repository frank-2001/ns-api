<?php
$requete= $bdd->prepare('SELECT * FROM livraison');	
$requete->execute();
$allCity=$requete->fetchAll();
for ($i=0; $i <count($allCity) ; $i++) { 
$allCity[$i]['Commune']=explode(',',$allCity[$i]['Commune']);
$allCity[$i]['Quartier']=explode('|',$allCity[$i]['Quartier']);
for ($on=0; $on < count($allCity[$i]['Quartier']); $on++) { 
$allCity[$i]['Quartier'][$on]=explode(',',$allCity[$i]['Quartier'][$on]);
}
}
// print_r($allCity);
$retour['resultat']=$allCity;
?>