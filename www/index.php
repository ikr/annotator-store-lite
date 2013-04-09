<?php

require_once __DIR__.'/../vendor/autoload.php';

use Silex\Application;

$app = new Application;

$app->get('/', function () use ($app) {
    return $app->json([
        'name' => 'Annotator Store API',
        'see' => 'https://github.com/okfn/annotator/wiki/Storage',
        'version' => '0.1.0'
    ]);
});

$app->run();
