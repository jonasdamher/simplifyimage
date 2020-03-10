<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>LibImagePhp</title>
	<link rel="stylesheet" type="text/css" href="public/css/main.css"/>
</head>
<body>
	<main>
		<div class="container">
			<h1 class="mb-2">LibImagePhp</h1>
			<div class="card sw-sm">
				<div class="card-body">
					<form action="upload.php" method="post" enctype="multipart/form-data">
						<label for="image">User image</label>
						<input id="image" name="image_user" type="file" />
						<button class="btn btn-orange sw-md" type="submit">Enviar</button>
					</form>
				</div>
			</div>
		</div>
	</main>
</body>
</html>