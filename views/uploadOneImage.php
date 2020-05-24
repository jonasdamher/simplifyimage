<?php

require '../vendor/autoload.php';

$libImagePhp = new Jonasdamher\Libimagephp\Image();

// BASIC METHODS
$libImagePhp->path->set('public/images/users/');
$libImagePhp->nameImputFile('image_user');

if (isset($_FILES['image_user'])) {

	// OPTIONS
	// $libImagePhp->required();

	// $libImagePhp->prefixName('myuser');

	// $libImagePhp->scale->set(200);

	// $libImagePhp->contrast->set('low');

	// $libImagePhp->crop->shape->set('square');
	// $libImagePhp->crop->position->set('right');

	// $libImagePhp->conversionTo('webp');

	// BASIC METHOD
	$upload = $libImagePhp->upload();
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