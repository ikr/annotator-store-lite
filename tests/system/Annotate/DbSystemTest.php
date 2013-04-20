<?php

namespace Annotate;

class DbSystemTest extends \PHPUnit_Framework_TestCase {
    public function testGlobalDbFileExists() {
        $this->assertFileExists(globalDbFilePath());
    }

    public function testGlobalDbObjectIsAvailable() {
        $db = globalDb();

        $stmt = $db->prepare(
            "select count(1) from sqlite_master where type = 'table' and name = 'annotations'"
        );

        $stmt->execute();

        $this->assertSame('1', $stmt->fetchColumn());
    }
}