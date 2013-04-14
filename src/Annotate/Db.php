<?php

namespace Annotate;

class Db {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function createSchema() {
        $this->pdo->exec(<<<EOF
create table annotations (
    id integer primary key autoincrement,
    json text,
    text text
)
EOF
        );

        return $this;
    }

    public function newIndexStatement() {
        return $this->pdo->prepare('select id, json from annotations');
    }

    public function index($pdoStatement) {
        $pdoStatement->execute();
        return $pdoStatement->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function newCreateStatement() {
        return $this->pdo->prepare('insert into annotations (json, text) values (:json, :text)');
    }

    public function create($pdoStatement, $json, $text) {
        $pdoStatement->bindValue(':json', $json, \PDO::PARAM_STR);
        $pdoStatement->bindValue(':text', $text, \PDO::PARAM_STR);
        $pdoStatement->execute();

        return $this->pdo->lastInsertId();
    }

    public function newReadStatement() {
        return $this->pdo->prepare('select json from annotations where id = :id');
    }

    public function read($pdoStatement, $id) {
        $pdoStatement->bindValue(':id', $id, \PDO::PARAM_INT);
        $pdoStatement->execute();

        return $pdoStatement->fetchColumn();
    }
}
