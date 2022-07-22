<?php
header("Access-Control-Allow-Origin: *");
header('Content-Type: application/json');
if ($_SERVER['SERVER_NAME']=="localhost") {
	require 'bdd.php';
}else{
	require 'https://nstore-db.000webhostapp.com/bdd.php';
}

require 'functions.php';
$retour=array();
if (isset($_GET['onLine'])){
	updateDataByid('users','actif',array('id',$_GET['onLine']),time(),$bdd);
}
if (isset($_GET['user'])) {
	require 'public/getUser.php';
}
elseif (isset($_GET['article'])) {
	require 'public/getArticle.php';
}
elseif (isset($_GET['pass'])) {
	require 'connexion.php';
}
elseif (isset($_GET['categories'])) {
	require 'getCategories.php';
}
elseif (isset($_GET['trieType'])) {
	require 'getTrieType.php';
}
elseif (isset($_GET['occasions'])) {
	require 'getOccasions.php';
}
elseif (isset($_GET['search'])) {
	require 'getSearchUser.php';
}
elseif (isset($_GET['commandUser'])) {
	require 'getNotification.php';
}
elseif (isset($_GET['oneUser'])) {
	require 'getOneUser.php';
}
elseif (isset($_GET['articleUser'])) {
	require 'getArticleUser.php';
}
elseif (isset($_GET['magasins'])) {
	require 'getMagasinUser.php';
}
elseif (isset($_GET['comment'])) {
	require 'getComment.php';
}
elseif (isset($_GET['heart'])) {
	require 'getHeart.php';
}
elseif (isset($_GET['subscribe'])) {
	require 'getSubscribe.php';
}
elseif (isset($_GET['conversation'])) {
	require 'conversation.php';
}
elseif (isset($_GET['saveImage'])) {
	require 'uploadImage.php';
}
elseif (isset($_GET['deleteImage'])) {
	require 'deleteImage.php';
}
elseif (isset($_GET['newArticle'])) {
	require 'newArticle.php';
}
elseif (isset($_GET['readNotification'])) {
	require 'readNotification.php';
}
elseif (isset($_GET['livraison'])) {
	require 'livraison.php';
}
elseif (isset($_GET['saveCmd'])) {
	require 'saveCmd.php';
}
elseif (isset($_GET['getMoney'])) {
	require 'getMoney.php';
}
elseif (isset($_GET['histo'])) {
	require 'histoPaiement.php';
}
elseif (isset($_GET['newEvent'])){
	require 'public/newEvent.php';
}
elseif (isset($_GET['getEvent'])){
	require 'public/getEvent.php';

}
elseif (isset($_GET['buyInvit'])){
	require 'buyInvit.php';
}
elseif (isset($_GET['getInvit'])){
	require 'getInvit.php';
}
elseif (isset($_GET['newShop'])){
	require 'newShop.php';
}
elseif (isset($_GET['articlenb'])){
	$retour['data']=count(getDataById('','article',['id','Desc'],$bdd));
}
elseif (isset($_GET['lastPosition'])){
	updateDataByid('users','lastPosition',array('id',$_GET['id']),$_GET['lastPosition'],$bdd);
}
elseif (isset($_GET['updateProfilImage'])){
	require 'updateProfilImage.php';
}elseif (isset($_GET['getUnread'])){
	require 'public/getUnread.php';
}elseif (isset($_GET['currentVersion'])){
	$retour['resultat']='1.0';
}elseif (isset($_GET['love'])) {
	$data=getData('datausers',['id_user',$_GET['love']],$bdd);
	$data[0]['heart']=explode(',', $data[0]['heart']);
	$data[0]['sub']=explode(',', $data[0]['sub']);
	$retour['data']=$data;
}
else{
	$retour["state"]=true;
	$retour["message"]="Vieuiller preciser votre demande en GET";
}
// require 'deplace.php';
// print_r(getDataById(0,'users',array('id','DESC'),$bdd));
// echo $code=md5("Frank").'</br>';
// echo md5($code);
// AES_DECRYP
// echo AES_DECRYPT($code);
// echo DATEDIFF('2014-01-09', '2014-01-01');
// echo realpath('index.php');
// $crypt=openssl_encrypt('1', "AES-128-ECB" ,'nunua-store@');
// echo $crypt.'----';
// $decrypt=openssl_decrypt($crypt, "AES-128-ECB" ,'nunua-store@');
// echo $decrypt.'---';
// print_r($retour);
// $test=transMoney('100','093838293','1000','$',$bdd);
// print_r($test);
// $token=token('61','i9wr079gLdwp04323hGGqQ==',$bdd);
// print_r($token);
// $data=getDataById('62','users','DESC',$bdd);
// print_r($data);
// print_r($retour);
// $retour=count(getDataById('','article',['id','Desc'],$bdd));
echo json_encode($retour);
?>