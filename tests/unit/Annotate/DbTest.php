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

    public function testNewReadStatementCallsPdoPrepareWithProperQuery() {
        self::db(
            m::mock()
                ->shouldReceive('prepare')
                ->with('select json from annotations where id = :id')
                ->once()
                ->getMock()
        )->newReadStatement();

        m::close();
    }

    public function testNewReadStatementReturnsTheResultOfPdosPrepare() {
        $stmt = new \stdClass;

        $this->assertSame(
            $stmt,

            self::db(
                m::mock()->shouldReceive('prepare')->andReturn($stmt)->getMock()
            )->newReadStatement()
        );
    }

    public function testReadBindsThePassedIdValue() {
        self::db()->read(
            m::mock()
                ->shouldIgnoreMissing()
                ->shouldReceive('bindValue')
                ->with(':id', 42, \PDO::PARAM_INT)
                ->once()
                ->getMock(),

            42
        );

        m::close();
    }

    public function testReadExecutesTheStatement() {
        self::db()->read(
            m::mock()
                ->shouldIgnoreMissing()
                ->shouldReceive('execute')
                ->withNoArgs()
                ->once()
                ->getMock(),

            33
        );

        m::close();
    }

    public function testReadReturnsTheResultOfFetchColumn() {
        $this->assertSame(
            'Lorem',

            self::db()->read(
                m::mock()
                    ->shouldIgnoreMissing()
                    ->shouldReceive('fetchColumn')
                    ->andReturn('Lorem')
                    ->getMock(),

                1704
            )
        );
    }

//--------------------------------------------------------------------------------------------------

    public function testNewIndexStatementInvokesTheDbsPrepareWithTheProperQuery() {
        self::db(
            m::mock()
                ->shouldReceive('prepare')
                ->with('select id, json from annotations')
                ->once()
                ->getMock()
        )->newIndexStatement();

        m::close();
    }

    public function testNewIndexStatementReturnsTheResultOfDbsPrepare() {
        $stmt = new \stdClass;

        $this->assertSame(
            $stmt,

            self::db(
                m::mock()
                    ->shouldReceive('prepare')
                    ->andReturn($stmt)
                    ->getMock()
            )->newIndexStatement()
        );
    }

    public function testIndexExecutesThePassedStatement() {
        self::db()->index(
            m::mock()
                ->shouldIgnoreMissing()
                ->shouldReceive('execute')
                ->withNoArgs()
                ->once()
                ->getMock()
        );

        m::close();
    }

    public function testIndexProperlyCallsTheStatementsFetchAll() {
        self::db()->index(
            m::mock()
                ->shouldIgnoreMissing()
                ->shouldReceive('fetchAll')
                ->with(\PDO::FETCH_ASSOC)
                ->once()
                ->getMock()
        );

        m::close();
    }

    public function testIndexReturnsTheResultOfFetchAll() {
        $rows = [['id' => 42, 'json' => '{}'], ['id' => 43, 'json' => '{}']];

        $this->assertSame(
            $rows,

            self::db()->index(
                m::mock()
                    ->shouldIgnoreMissing()
                    ->shouldReceive('fetchAll')
                    ->andReturn($rows)
                    ->getMock()
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
