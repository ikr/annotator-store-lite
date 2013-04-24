<?php

namespace Annotate;

use Mockery as m;

class ControllerTest extends \PHPUnit_Framework_TestCase {
    public function testIndexDelegatesToTheDb() {
        $stmt = new \stdClass;

        self::c(
            m::mock()
                ->shouldReceive('newIndexStatement')->withNoArgs()->once()->andReturn($stmt)
                ->ordered()
                ->shouldReceive('index')->with($stmt)->once()
                ->andReturn([])
                ->ordered()
                ->getMock()
        )->index();

        m::close();
    }

    public function testIndexMergesTheIdWithTheDbsJsonAndReturnsAsTheResponseData() {
        $this->assertEquals(
            [['id' => 42, 'foo' => 'bar']],

            self::c(
                m::mock()
                    ->shouldIgnoreMissing()
                    ->shouldReceive('index')
                    ->andReturn([['id' => 42, 'json' => '{"foo":"bar"}']])
                    ->getMock()
            )->index()['data']
        );
    }

    public function testIndexIsNormally200() {
        $this->assertSame(200, self::c(self::dbStub())->index()['status']);
    }

    public function testIndexesHeadersMapIsEmpty() {
        $this->assertSame([], self::c(self::dbStub())->index()['headers']);
    }

//--------------------------------------------------------------------------------------------------

    public function testCreateDelegatesToTheDb() {
        $stmt = new \stdClass;

        self::c(
            m::mock()
            ->shouldReceive('newCreateStatement')->withNoArgs()->once()->andReturn($stmt)
            ->ordered()
            ->shouldReceive('create')->with($stmt, '{"text":"S12"}', 'S12')->once()
            ->ordered()
            ->getMock()
        )->create(['text' => 'S12']);
    }

//--------------------------------------------------------------------------------------------------

    private static function c($db) {
        return new Controller($db, 'http://example.com/foo');
    }

    private static function dbStub() {
        return m::mock()->shouldIgnoreMissing()->shouldReceive('index')->andReturn([])->getMock();
    }
}
