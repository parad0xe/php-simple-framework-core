# PHP-FRAMEWORK-CORE

## Installation

Create directory `libs` in your root project

Download repository in `/libs` directory

```bash
git clone https://github.com/parad0xe/php-simple-framework-core.git
```

In your composer.json add:

```json
{
    "repositories": [
        {
            "type": "path",
            "url": "libs/php-simple-framework-core"
        }
    ]
}
```

Then, install it:

```bash
composer require parad0xe/php-simple-framework-core
```

## Configuration

Add a configuration file (`app_configuration.php`) in your root project

Paste default configuration:

`app_configuration.php`

```php
<?php

return [
    "app_root_dir" => __DIR__,
    "app_public_dir" => __DIR__,
    "app_page_dir" => __DIR__ . "/pages",
    "database" => [
        "connect_database" => false,
        "user" => "",
        "password" => "",
        "database" => "",
        "port" => "3306",
        "host" => "localhost"
    ],
    "first_connection_cookiekey" => "__fc"
];
```

## Usage

Create directory `pages` in your root project directory\
Create directory `Controller` in your src project directory

### Index

```php
<?php

require 'vendor/autoload.php';

use Parad0xeSimpleFramework\Core\Application;
use Parad0xeSimpleFramework\Core\Request\Request;

$app = new Application(__DIR__);
try {
    $response = $app->dispatch(new Request($_POST, $_GET));
} catch (Exception $e) {
    die($e->getMessage());
}
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
        <?= $response->render() ?>
    </body>
</html>
```

### Controller

```php
<?php

namespace App\Controller;

use Parad0xeSimpleFramework\Core\AbstractController;
use Parad0xeSimpleFramework\Core\Route\Route;

class ApiController extends AbstractController
{
    public ?array $routes_request_auth = [
        "api:index" => false,
        "api:time" => false
    ];

    #[Route("api:index", "/api/index", "api/index")]
    public function index() {
        return $this->render("api/index", [
            "name" => "Hello World"
        ]); // return 'pages/api/index.php' (with args: $name)
    }

    #[Route("api:time", "/api/time/:id/:slug", null, ["id" => ["default" => 1, "regex" => "\d+"],"slug" => ["default" => "james", "regex" => "[a-zA-Z]+(-[a-zA-Z0-9]+)*"]])]
    public function getTime(int $id, string $slug) {
        return $this->json([
            "id" => $id,
            "slug" => $slug,
            "time" => time()
        ]);
    }
}
```

> The framework return `pages/errors/404.php` if the requested url does not correspond to any route
