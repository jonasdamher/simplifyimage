<?php

require_once 'libs/libimagephp.php';

$libImage->setPath('public/images/users/');
$libImage->setNameInputFile('image_user');
$libImage->requiredImage();

// $libImage->setCropPosition('top');

$libImage->setCropType('square');
// $libImage->setScale(128);
$libImage->setConversionTo('png');

$upload = $libImage->uploadNewImage();

if($upload['valid']) {

	header('location: index.php');

}else {

	print_r($upload);

}

?>