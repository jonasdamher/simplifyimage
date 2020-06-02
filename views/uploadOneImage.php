<?php

require '../vendor/autoload.php';

$simplifyImage = new Jonasdamher\Simplifyimage\Image();

// BASIC METHODS
$simplifyImage->path->set('../public/images/users/');
$simplifyImage->nameImputFile('image_user');

if (isset($_FILES['image_user'])) {

	// OPTIONS
	// $simplifyImage->required();

	// $simplifyImage->prefixName('myuser');

	// $simplifyImage->scale->set(200);

	// $simplifyImage->contrast->set('low');

	// $simplifyImage->crop->shape->set('square');
	// $simplifyImage->crop->position->set('right');

	// $simplifyImage->conversionTo('webp');

	// BASIC METHOD
	$upload = $simplifyImage->upload();
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>LibImagePHP | Upload one image</title>
	<link rel="stylesheet" type="text/css" href="../public/css/main.css" />
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
			<a class="link mt-2 text-bold text-sw-sm" href="../index.php" title="Back">Back</a>
		</div>
	</main>
	<?php if (isset($upload)) { ?>
		<pre>
			<?php print_r($upload); ?>
		</pre>
	<?php } ?>

</body>

</html>