<?php

use Dotenv\Dotenv;
use Pimple\Container;
use Silex\Application;
use Silex\Provider\DoctrineServiceProvider;
use Silex\Provider\ServiceControllerServiceProvider;

require 'vendor/autoload.php';

$dotenv = new Dotenv(__DIR__);
$dotenv->load();

$app = new Application();
$app->register(new DoctrineServiceProvider(), [
  'db.options' => [
    'driver' => $_SERVER['DB_DRIVER'],
    'host' => $_SERVER['DB_HOST'],
    'dbname' => $_SERVER['DB_NAME'],
    'user' => $_SERVER['DB_USER'],
    'password' => $_SERVER['DB_PASSWORD'],
  ],
]
);
$app->register(new ServiceControllerServiceProvider());
$app['controller.school'] = function (Container $c) {
  return new SchoolController($c['db']);
};
$app->get('/schools/{id}', 'controller.school:getAction');
$app->get('/schools', 'controller.school:cgetAction');
$app->run();
