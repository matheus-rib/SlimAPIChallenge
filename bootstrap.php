<?php
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

require 'vendor/autoload.php';

$settings = require 'config/settings.php';
$container = new \Slim\Container($settings);

//Eloquent Config
$container['db'] = function ($container) {
    $capsule = new \Illuminate\Database\Capsule\Manager;
    $capsule->addConnection($container['settings']['db']);

    $capsule->setAsGlobal();
    $capsule->bootEloquent();
    return $capsule;
};
$container['db'];

$app = new \Slim\App($container);

// Products Routes
require 'src/routes/products.php';

$app->run();