<?php
if(isset($_GET['heartNotAll'])){
$requete = $bdd->prepare('UPDATE notification SET state= :state where creator=\''.$_GET['id'].'\'');
		$requete->execute(array(
			'state' =>1,
		));	
}
if(isset($_GET['heartNot'])){
$requete = $bdd->prepare('UPDATE notification SET state= :state where inde=\''.$_GET['id'].'\'');
		$requete->execute(array(
			'state' =>1,
		));
}
if (isset($_GET['confirmCmd'])) {
		require 'confirmCmd.php';}
if (isset($_GET['confirmCmdError'])) {
		require 'confirmCmd.php';}
// $requete= $bdd->prepare('SELECT * FROM notification where inde=\''.$_GET['id'].'\'');	
// 		$requete->execute();
// print_r($requete->fetchAll());
?>