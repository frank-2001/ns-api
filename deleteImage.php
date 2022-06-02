<?php
if (isset($_GET['event']) OR isset($_GET['profil'])) {
unlink('fichier/waitingImages/'.$_GET['deleteImage']);
}else{
unlink('fichier/Upload/'.$_GET['deleteImage']);	
}
$retour['resultat']=$_GET['deleteImage'].' supprimer avec succes';
?>