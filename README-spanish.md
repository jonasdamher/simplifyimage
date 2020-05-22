# libimagephp
### Subir, modificar y eliminar imagenes de manera facil.

* Permite recortar y escalar imagenes facilmente.
* Permite subir y actualizar una o multiples imagenes.
* Permite conversion de tipo de imagen a WEBP, PNG y JPEG.

[libimagephp en packagist](https://packagist.org/packages/jonasdamher/libimagephp)

### Requisitos

* Versión recomendada de PHP 7.4.2
* Versión recomendada de GD bundled (2.1.0 compatible)

### Instalar librería libimagephp en tu proyecto con composer

```
composer require jonasdamher/libimagephp
```

### Primer paso

Cargar autoload de composer y especificar la ruta de la libreria libimagephp.

```
require __DIR__ . '/vendor/autoload.php';

$libimagephp = new Jonasdamher\Libimagephp\Image();
```

## Funcionalidades 

### Funciones básicas

Especificar ruta donde se guardarán las imagenes.

```
$libimagephp->path->set('public/images/users/');
```

Especificar el nombre del input del formulario de donde recogerá las imagenes (en este caso el input se llama "image_user").

```
$libimagephp->nameImputFile('image_user');
```

Subir imagen.

```
$upload = $libimagephp->upload();
```

^ La variable $upload devuelve un array con el siguiente formato.

Si es correcto.

```
[
  "valid" => true,
  "filename" => "myImage.jpeg",
  "errors" => []
]
```

Si no es correcto y hay un fallo, por ejemplo.

```
[
  "valid" => false,
  "filename" => null,
  "errors" => ["It could not image upload, try again."]
]
```

Borrar imagen (Experimental).

```
$libimagephp->setOldImageName('myImage.png');

$remove = $libimagephp->remove();
```

^ La variable $remove devuelve un array, como al subir una imagen.

### Funciones opcionales

**Establecer tamaño máximo de imagen

Especifica el tamañano máximo de la imagen, se debe especificar en bytes, por defecto son *2097152 bytes* (2 MB).

```
$libimagephp->maxSize(1000000);
```

**Escalar

Escalar imagen (tiene 2 parametros, ancho y alto, si se le especifica solo el ancho el alto será igual al ancho).

```
$libimagephp->scale->set(200, 320);
```

**Contraste

Especificar contraste de la imagen (tiene la opción low, medium, hight).

```
$libimagephp->contrast->set('low');
```

**Conversión de imagen

Transformar imagen a formato webp, png o jpeg.

```
$libimagephp->conversionTo('webp');
```

**Recorte

Forma de recorte de imagen (opción square, circle, h_rectangle, v_rectangle )

```
$libimagephp->crop->shape->set('square');
```

Especificar la posición de recorte en la image (left, right, top, bottom, center) (Experimental).

```
$libimagephp->crop->position->set('right');
```

**Requerir imagen

Especificar que la imagen sea obligatoria.

```
$libimagephp->required();
```

**Prefijo de nombre

Especificar si en el nombre de la imagen quieres asignarle un prefijo (por ejemplo que tenga el prefijo user, la imagen quedaría con el nombre "user-imageexample.jpg").

```
$libImage->prefixName('user');
```

### Autor

Jonás Damián Hernández [jonasdamher]
