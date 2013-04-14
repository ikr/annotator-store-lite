<?php

namespace Annotate;

class DbIntegrationTest extends \PHPUnit_Framework_TestCase {
    public function testNewCreateStatementWorksOnRamDb() {
        self::ramDb()->newCreateStatement();
    }

    public function testCreateAndReadWorkOnRamDb() {
        $db = self::ramDb();

        $this->assertSame(
            '1',
            $db->create($db->newCreateStatement(), '{}', 'This is the text')
        );

        $this->assertSame('{}', $db->read($db->newReadStatement(), 1));
        $this->assertFalse($db->read($db->newReadStatement(), 42));
    }

    public function testIndexSqlSyntaxIsCorrect() {
        $db = self::ramDb();
        $db->index($db->newIndexStatement());
    }

//--------------------------------------------------------------------------------------------------

    private static function ramDb() {
        $pdo = new \PDO('sqlite::memory:');
        $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

        return (new Db($pdo))->createSchema();
    }
}
