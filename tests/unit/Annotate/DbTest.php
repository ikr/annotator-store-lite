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
                m::mock()->shouldReceive('prepare')->andReturn($stmt)->getMock()
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

    public function testNewUpdateStatementCallsPdosPrepareWithProperQuery() {
        self::db(
            m::mock()
                ->shouldReceive('prepare')
                ->with('update annotations set json = :json, text = :text where id = :id')
                ->once()
                ->getMock()
        )->newUpdateStatement();

        m::close();
    }

    public function testNewIndexSatementReturnsTheResultOfPrepare() {
        $stmt = new \stdClass;

        $this->assertSame(
            $stmt,

            self::db(
                m::mock()->shouldReceive('prepare')->andReturn($stmt)->getMock()
            )->newUpdateStatement()
        );
    }

    public function testUpdateBindsAllTheStatementValues() {
        self::db()->update(
            m::mock()
                ->shouldIgnoreMissing()
                ->shouldReceive('bindValue')->with(':id', 42, \PDO::PARAM_INT)->once()
                ->ordered()
                ->shouldReceive('bindValue')->with(':json', '{}', \PDO::PARAM_STR)->once()
                ->ordered()
                ->shouldReceive('bindValue')->with(':text', 'Z', \PDO::PARAM_STR)->once()
                ->ordered()
                ->getMock(),

            42,
            '{}',
            'Z'
        );

        m::close();
    }

    public function testUpdateExecutesThePdoStatement() {
        self::db()->update(
            m::mock()
                ->shouldIgnoreMissing()
                ->shouldReceive('execute')
                ->withNoArgs()
                ->once()
                ->getMock(),

            43,
            '{"a"": 13}',
            'Lorem lorem'
        );

        m::close();
    }

//--------------------------------------------------------------------------------------------------
    public function testNewDeleteStatementCallsPdosPrepareWithTheProperQuery() {
        self::db(
            m::mock()
                ->shouldReceive('prepare')
                ->with('delete from annotations where id = :id')
                ->once()
                ->getMock()
        )->newDeleteStatement();

        m::close();
    }

    public function testNewCreateStatementReturnsTheResultOfThePrepareCall() {
        $stmt = new \stdClass;

        $this->assertSame(
            $stmt,

            self::db(
                m::mock()->shouldReceive('prepare')->andReturn($stmt)->getMock()
            )->newDeleteStatement()
        );
    }

    public function testDeleteBindsTheIdValueAgainstThePassedPdoStatement() {
        self::db()->delete(
            m::mock()
                ->shouldIgnoreMissing()
                ->shouldReceive('bindValue')
                ->with(':id', 2037, \PDO::PARAM_INT)
                ->once()
                ->getMock(),

            2037
        );

        m::close();
    }

    public function testDeleteExecutesThePdoStatement() {
        self::db()->delete(
            m::mock()
                ->shouldIgnoreMissing()
                ->shouldReceive('execute')
                ->withNoArgs()
                ->once()
                ->getMock(),

            2045
        );

        m::close();
    }

//--------------------------------------------------------------------------------------------------

    private static function db($pdo = null) {
        return new Db(
            $pdo ?: m::mock()->shouldIgnoreMissing()->shouldReceive('lastInsertId')->getMock()
        );
    }
}
