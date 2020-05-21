# libimagephp
### Subir, modificar y eliminar imagenes de manera facil.

* Permite recortar y escalar imagenes facilmente.
* Permite subir y actualizar una o multiples imagenes.
* Permite conversion de tipo de imagen a WEBP, PNG, JPG y JPEG.

Versión 1.0

### Requisito

Versión recomendada de PHP 7.4.2

Versión recomendada de GD bundled (2.1.0 compatible)

### Instalar librería libimagephp en tu proyecto con composer

```
composer require jonasdamher/libimagephp
```

### Primer paso

Cargar el autoload de composer y especificar la ruta de la libreria libimagephp.

```
require __DIR__ . '/vendor/autoload.php';

$libimagephp = new Jonasdamher\Libimagephp\Image();
```

## Funcionalidades 

### Configuración básica

Especificar ruta donde se guardarán las imagenes.

```
$libimagephp->path->set('public/images/users/');
```

Especificar el nombre del input del formulario de donde recogerá las imagenes (en este caso el input se llama "image_user").

```
$libimagephp->nameImputFile('image_user');
```

Especificar si es necesario que la imagen sea obligatoria.

```
$libimagephp->required();
```

Especificar si en el nombre de la imagen quieres asignarle un prefijo (por ejemplo que tenga el prefijo user, la imagen quedaría con el nombre "user-imageexample.jpg").

```
$libImage->prefixName('user');
```

### Autor

Jonás Damián Hernández [jonasdamher]
