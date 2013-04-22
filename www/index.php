<?php

namespace Annotate;

require __DIR__.'/../vendor/autoload.php';

use \Silex\Application;

$app = new Application;

$app['db'] = $app->share(function () {
    return globalDb();
});

$app->get('/', function () use ($app) {
    return $app->json([
        'name' => 'Annotator Store API',
        'see' => 'https://github.com/okfn/annotator/wiki/Storage',
        'version' => '0.1.0'
    ]);
});

$app->get('/annotations', function () use ($app) {
    return delegateToController($app, 'index');
});

$app->run();

//--------------------------------------------------------------------------------------------------

function delegateToController($app, $method) {
    $result = (new Controller(new Db($app['db'])))->$method();
    return $app->json($result['data'], $result['status'], $result['headers']);
}
