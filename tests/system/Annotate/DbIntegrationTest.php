<?php

namespace Annotate;

class DbIntegrationTest extends \PHPUnit_Framework_TestCase {
    public function testNewCreateStatementWorksOnRamDb() {
        self::ramDb()->newCreateStatement();
    }

    public function testCreateWorksOnRamDb() {
        $db = self::ramDb();

        $this->assertSame(
            '1',

            $db->create(
                $db->newCreateStatement(),
                '{}',
                'This is the text'
            )
        );
    }

//--------------------------------------------------------------------------------------------------

    private static function ramDb() {
        $pdo = new \PDO('sqlite::memory:');
        $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

        return (new Db($pdo))->createSchema();
    }
}
