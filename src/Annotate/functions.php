<?php

namespace Annotate;

function loadConfigurationInto($app) {
    foreach (json_decode(file_get_contents(__DIR__ . '/../../CONFIG.json', true)) as $k => $v) {
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
    return realpath(__DIR__ . '/../../data/db.sqlite');
}