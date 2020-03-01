<?php 

require_once 'libs/LibImage.php';

$libImage = new LibImage();

$libImage->setPath('public/images/users/');
$libImage->setNameInputFile('image_user');

print_r($libImage->uploadNewImage() );

?>