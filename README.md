# PHP-SIMPLE-FRAMEWORK

## Requirements

### PHP

>php \>= 8.0

### Yaml

If you don't have yaml extension


Install pecl

```bash
sudo apt-get install php-pear
```

Then, install yaml extension

```bash
pecl install yaml
```

And add in your php.ini:

```ini
extension=yaml.so
```

## Installation

Create empty project

```bash
mkdir <project_name>
cd <project_name>
```

Init composer

```bash
composer init
```

In your composer.json add:

```json
{
    "autoload": {
        "psr-4": {
            "App\\": "src/"
        }
    },
    "repositories": [
        {
            "type": "path",
            "url": "libs/php-simple-framework-core"
        }
    ],
    "scripts": {
        "framework:install": "composer dump-autoload && mkdir libs && git -C libs clone https://github.com/parad0xe/php-simple-framework-core.git && composer require parad0xe/php-simple-framework-core && cp -R libs/php-simple-framework-core/assets/* .",
        "framework:update": "rm -rf libs/php-simple-framework-core && git -C libs clone https://github.com/parad0xe/php-simple-framework-core.git"
    }
}
```

Then, install it:

```bash
composer run framework:install
```

Run the server

```bash
php -S localhost:3000
```

Now, you can access it with the url: `http://localhost:3000`

## Usage

### Index

```php
<?php

require 'vendor/autoload.php';

use Parad0xeSimpleFramework\Core\SimpleApplication;

$app = new SimpleApplication(__DIR__);
?>

<!doctype html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Document</title>
    </head>
    <body>
        <?= $app->getResponse()->render() ?>
    </body>
</html>
```

### Controller

> All Controller name must end with 'Controller'

> The framework return `pages/errors/404.php` if the requested url does not correspond to any route

`src/Controller/FooController.php`

```php
<?php

namespace App\Controller;

use Parad0xeSimpleFramework\Core\Http\Controller\AbstractController;
use Parad0xeSimpleFramework\Core\Route\Route;
use Parad0xeSimpleFramework\Core\Route\RouteMethod;

class FooController extends AbstractController
{
    public ?array $routes_request_auth = [
        "foo:index" => false,
        "foo:post:view" => false
    ];

    #[Route("foo:index", "/foo/index")]
    public function index() {
        return $this->render("foo/index", [
            "name" => "Hello World"
        ]); // return 'pages/foo/index.php' (with args: $name)
    }

    #[Route("foo:post:view", "/foo/post/:id/:slug", ["id" => ["default" => 1, "regex" => "\d+"],"slug" => ["default" => "james", "regex" => "[a-zA-Z]+(-[a-zA-Z0-9]+)*"]])]
    #[RouteMethod("get", "post")]    
    public function view(int $id, string $slug) {
        return $this->json([
            "id" => $id,
            "slug" => $slug,
            "content" => "lorem ipsum",
            "time" => time()
        ]);
    }
}
```
