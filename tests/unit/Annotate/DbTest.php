<?php

namespace Annotate;

use Mockery as m;

class DbTest extends \PHPUnit_Framework_TestCase {
    public function testCreateSchemaReturnsTheDbObjectItself() {
        $db = self::db();
        $this->assertSame($db, $db->createSchema());
    }

    public function testCreateSchemaExecutesACreateTable() {
        self::db(
            m::mock()
                ->shouldReceive('exec')
                ->with('/^create table annotations/')
                ->once()
                ->getMock()
        )->createSchema();

        m::close();
    }

//--------------------------------------------------------------------------------------------------

    public function testNewCreateStatementCallsPdoPrepareWithProperQuery() {
        self::db(
            m::mock()
                ->shouldReceive('prepare')
                ->with('insert into annotations (json, text) values (:json, :text)')
                ->once()
                ->getMock()
        )->newCreateStatement();

        m::close();
    }

    public function testNewCreateStatementReturnsTheResultOfPdosPrepare() {
        $stmt = new \stdClass;

        $this->assertSame(
            $stmt,

            self::db(
                m::mock()->shouldReceive('prepare')->andReturn($stmt)->getMock()
            )->newCreateStatement()
        );
    }

    public function testCreateBindsTheJsonAndTextValue() {
        self::db()->create(
            m::mock()
                ->shouldIgnoreMissing()
                ->shouldReceive('bindValue')->with(':json', '{}', \PDO::PARAM_STR)->once()
                ->ordered()
                ->shouldReceive('bindValue')->with(':text', 'Lorem ipsum', \PDO::PARAM_STR)->once()
                ->ordered()
                ->getMock(),

            '{}',
            'Lorem ipsum'
        );

        m::close();
    }

    public function testCreateExecutesTheStatement() {
        self::db()->create(
            m::mock()
                ->shouldIgnoreMissing()
                ->shouldReceive('execute')
                ->withNoArgs()
                ->once()
                ->getMock(),
            '',
            ''
        );

        m::close();
    }

    public function testCreateReturnsTheLastInsertId() {
        $this->assertSame(
            42,

            self::db(
                m::mock()->shouldReceive('lastInsertId')->withNoArgs()->andReturn(42)->getMock()
            )->create(
                m::mock()->shouldIgnoreMissing()->shouldReceive('bindValue')->getMock(),
                '',
                ''
            )
        );
    }

//--------------------------------------------------------------------------------------------------

    private static function db($pdo = null) {
        return new Db(
            $pdo ?: m::mock()->shouldIgnoreMissing()->shouldReceive('lastInsertId')->getMock()
        );
    }
}
