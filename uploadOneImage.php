<?php 
if(isset($_FILES['image_user']) ) {

	require_once 'libs/libimagephp.php';

	$libImage->setPath('public/images/users/');
	$libImage->setNameInputFile('image_user');
	$libImage->requiredImage();

	// $libImage->setCropPosition('right');
	$libImage->setCropType('circle');
	$libImage->setConversionTo('png');

	$upload = $libImage->uploadImage();

	if($upload['valid']) {

		header('location: uploadOneImage.php');

	}else {

		print_r($upload);

	}

}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>LibImagePHP | Upload one image</title>
	<link rel="stylesheet" type="text/css" href="public/css/main.css"/>
</head>
<body>
	<main>
		<div class="container">
			<h1 class="mb-2">Upload one image</h1>
			<div class="card sw-sm">
				<div class="card-body">
					<form class="form-v" action="uploadOneImage.php" method="post" enctype="multipart/form-data">
						<div class="input-group">
							<label class="label" for="image">Image</label>
							<input id="image" name="image_user" type="file" />
						</div>
						<button class="btn btn-orange sw-md text-bold" type="submit">Upload</button>
					</form>
				</div>
			</div>
			<a class="link mt-2 text-bold text-sw-sm" href="index.php" title="Back">Back</a>
		</div>
	</main>
</body>
</html>
