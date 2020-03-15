<?php

require_once 'libs/libimagephp.php';

$libImage->requireImage();
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