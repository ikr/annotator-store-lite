<?php

require_once __DIR__.'/../vendor/autoload.php';

use Silex\Application;

$app = new Application;

$app->get('/', function () use ($app) {
    return 'Превед!';
});

$app->run();
