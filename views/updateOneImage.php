<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>SimplifyImage | Update one image</title>
	<link rel="stylesheet" type="text/css" href="../public/css/main.css"/>
</head>
<body>
	<main>
		<div class="container">
			<h1 class="mb-2">Update one image</h1>
			<div class="card sw-sm">
				<div class="card-body">
					<form class="form-v" action="updateOneImage.php" method="post" enctype="multipart/form-data">
						<div class="input-group">
							<label class="label" for="image">Image</label>
							<input id="image" name="image_user" type="file" />
						</div>
						<button class="btn btn-orange sw-md text-bold" type="submit">Update</button>
					</form>
				</div>
			</div>
			<a class="link mt-2 text-bold text-sw-sm" href="../index.php" title="Back">Back</a>
		</div>
	</main>
</body>
</html>
