<?php
// Commpresse image
function compressImage($source,$destination,$quality){
	#Get image info
	$imgInfo=getimagesize($source);
	$mime=$imgInfo['mime'];
	#Create a new image from file
	switch ($mime) {
		case 'image/jpeg':
			$image =imagecreatefromjpeg($source);
			break;
		case 'image/png':
			$image =imagecreatefrompng($source);
			break;
		case 'image/gif':
			$image =imagecreatefromgif($source);
			break;
		
		default:
		$image =imagecreatefromjpeg($source);
			break;
	}	
	#Save image
	imagejpeg($image,$destination,$quality);
	#return Compressed imag
	return $destination;
}

$ds = DIRECTORY_SEPARATOR;  //1
$storeFolder = 'uploads';   //2
if (!empty($_FILES)) {
    $tempFile = $_FILES['Myfile']['tmp_name'];
    $file_name = $_FILES['Myfile']['name'];
    $ext = pathinfo($file_name, PATHINFO_EXTENSION);

    if($ext == 'jpg' || $ext == 'png' || $ext == 'JPG' || $ext == 'PNG' || $ext == 'jpeg' || $ext == 'gif' ){
        $new_file_name = 'Nunua-store'.time().'.'.$ext;
		if (isset($_GET['event']) OR isset($_GET['profil'])) {
			$targetPath="fichier/waitingImages/";
		}else{
			$targetPath="fichier/Upload/";			
		}
        $targetFile =  $targetPath. $file_name;

		$compressedImage = compressImage($tempFile,$targetFile,15);

            	// echo $new_file_name;
		$retour['resultat']=$file_name;

    }else{
    	$retour['resultat']="Echec";
        // echo 'FAILED';
    }
}