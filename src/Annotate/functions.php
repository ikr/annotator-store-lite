<?php

namespace Annotate;

function globalDb() {
    $pdo = new \PDO('sqlite:' . globalDbFilePath());
    $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
    return $pdo;
}

function globalDbFilePath() {
    return realpath(__DIR__ . '/../../data/db.sqlite');
}