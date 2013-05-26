<?php

namespace Annotate;

require __DIR__.'/../vendor/autoload.php';

use \Silex\Application;
use Symfony\Component\HttpFoundation\Request;

$app = loadConfigurationInto(new Application);

$app['db'] = $app->share(function () {
    return globalDb();
});

$app->error(function (\Exception $ex, $code) use ($app) {
    return $app->json([
        'statusCode' => $code,
        'message' => $ex->getMessage(),
        'stackTrace' => $ex->getTrace()
    ], $code);
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

$app->post('/annotations', function (Request $req) use ($app) {
    return delegateToController($app, 'create', json_decode($req->getContent(), true));
});

$app->get('/annotations/{id}', function ($id) use ($app) {
    return delegateToController($app, 'read', $id);
});

$app->run();

//--------------------------------------------------------------------------------------------------

function delegateToController($app, $method, $param) {
    $result = (
        new Controller(new Db($app['db']), $app['apiRootUrlWithoutTrailingSlash'])
    )->$method($param);

    return $app->json($result['data'], $result['status'], $result['headers']);
}
