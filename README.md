# libimagephp
### Upload, modify and delete images the easy way. 

* Allows crop and scale images easy. 
* Allows upload and update one or multiple images. 
* Allows conversion type image to WEBP, PNG, JPG and JPEG.

[Do you speak Spanish? Go to README-spanish.md](https://github.com/jonasdamher/libimagephp/blob/master/README-spanish.md)

:memo: [License MIT](https://github.com/jonasdamher/libimagephp/blob/master/LICENSE) | :ballot_box_with_check: 
[libimagephp in packagist](https://packagist.org/packages/jonasdamher/libimagephp)

### Requirements

* Recommend version PHP 7.4.2
* Recommend version GD bundled (2.1.0 compatible)

### Install libimagephp library in your project with composer

```
composer require jonasdamher/libimagephp
```

### First step

Load composer autoload and specify path the libimagephp library.

```
require __DIR__ . '/vendor/autoload.php';

$libimagephp = new Jonasdamher\Libimagephp\Image();
```

## Functionalities 

### Basic functions

Specify the path where the images will be saved.

```
$libimagephp->path->set('public/images/users/');
```

Specify input name of the form.

```
$libimagephp->nameImputFile('image_user');
```

Upload image.

```
$upload = $libimagephp->upload();
```

### Author

Jonás Damián Hernández [jonasdamher]
