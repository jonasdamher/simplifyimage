<?php 

require_once 'libs/libimagephp/LibImage.php';

$libImage = new LibImage();

// $libImage->requireImage();
$libImage->setPath('public/images/users/');
$libImage->setNameInputFile('image_user');

// $libImage->setScale(512);
// $libImage->setConversionTo('webp');

print_r($libImage->uploadNewImage() );

?>