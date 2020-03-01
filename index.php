<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>LibImage</title>
</head>
<body>
	<form action="upload.php" method="post" enctype="multipart/form-data">
		<label for="image">Imagen de usuario</label>
		<input id="image" name="image_user" type="file" />
		<button type="submit">Enviar</button>
	</form>
</body>
</html>