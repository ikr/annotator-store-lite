<?php

require __DIR__ . '/../vendor/autoload.php';

$pdo = new PDO('sqlite:' . Annotate\globalDbFilePath());
$pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
(new Annotate\Db($pdo))->createSchema();
