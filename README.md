# libimagephp

Upload, modify and delete images the easy way. 

* Allows crop and scale images easy. 
* Allows upload and update one or multiple images. 
* Allows conversion type image to WEBP, PNG, JPG and JPEG.

![Packagist Version](https://img.shields.io/packagist/v/jonasdamher/libimagephp)
[![GitHub issues](https://img.shields.io/github/issues/jonasdamher/libimagephp)](https://github.com/jonasdamher/libimagephp/issues) 
[![GitHub forks](https://img.shields.io/github/forks/jonasdamher/libimagephp)](https://github.com/jonasdamher/libimagephp/network) 
[![GitHub stars](https://img.shields.io/github/stars/jonasdamher/libimagephp)](https://github.com/jonasdamher/libimagephp/stargazers)

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

[![GitHub license](https://img.shields.io/github/license/jonasdamher/libimagephp)](https://github.com/jonasdamher/libimagephp/blob/master/LICENSE)
