<?php 

require_once 'libs/libimagephp/LibImage.php';

$libImage = new LibImage();

// $libImage->requireImage();
$libImage->setPath('public/images/users/');
$libImage->setNameInputFile('image_user');
$libImage->requireImage();

// $libImage->setScale(512);
// $libImage->setConversionTo('webp');

$upload = $libImage->uploadNewImage();

if($upload['valid']) {

	header('location: index.php');

}else {
	
	print_r($upload);

}

?>