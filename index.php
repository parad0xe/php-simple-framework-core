<?php

use Parad0xeSimpleFramework\Core\Application;
use Parad0xeSimpleFramework\Core\Request\Request;

require 'vendor/autoload.php';

$app = new Application();
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
