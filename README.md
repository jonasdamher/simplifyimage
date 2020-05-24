# libimagephp

Upload, modify and delete images the easy way. 

* Allows crop and scale images easy. 
* Allows upload and update one or multiple images. 
* Allows conversion type image to WEBP, PNG, JPG and JPEG.

:link: [Do you speak Spanish? Go to README-spanish.md](https://github.com/jonasdamher/libimagephp/blob/master/README-spanish.md)

## Requirements

* Recommend version PHP 7.4.2
* Recommend version GD bundled (2.1.0 compatible)

## Installation

```
composer require jonasdamher/libimagephp
```

## Usage

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

## Contributing

Jonás Damián Hernández [jonasdamher]

## License

[MIT](https://github.com/jonasdamher/libimagephp/blob/master/LICENSE) 
