<?php
require 'vendor/autoload.php';

// Load DB Settings
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

// Routes
require_once 'src/routes/products.php';
require_once 'src/routes/customers.php';
require_once 'src/routes/orders.php';

$app->run();