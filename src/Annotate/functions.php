<?php

namespace Annotate;

function loadConfigurationInto($app) {
    foreach (config() as $k => $v) {
        $app[$k] = $v;
    }

    return $app;
}

function globalDb() {
    $pdo = new \PDO('sqlite:' . globalDbFilePath());
    $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
    return $pdo;
}

function globalDbFilePath() {
    return config()['dbFilePath'];
}

function config()
{
    return json_decode(file_get_contents(__DIR__ . '/../../CONFIG.json'), true);
}
