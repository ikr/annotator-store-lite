<?php

namespace Annotate;

use Mockery as m;

class ControllerTest extends \PHPUnit_Framework_TestCase {
    public function testIndexDelegatesToTheDb() {
        $stmt = new \stdClass;

        (new Controller(
            m::mock()
                ->shouldReceive('newIndexStatement')->withNoArgs()->once()->andReturn($stmt)
                ->ordered()
                ->shouldReceive('index')->with($stmt)->once()
                ->andReturn([])
                ->ordered()
                ->getMock()
        ))->index();

        m::close();
    }

    public function testIndexMergesTheIdWithTheDbsJsonAndReturnsAsTheResponseData() {
        $this->assertEquals(
            [['id' => 42, 'foo' => 'bar']],

            (new Controller(
                m::mock()
                    ->shouldIgnoreMissing()
                    ->shouldReceive('index')
                    ->andReturn([['id' => 42, 'json' => '{"foo":"bar"}']])
                    ->getMock()
            ))->index()['data']
        );
    }

    public function testIndexIsNormally200() {
        $this->assertSame(200, (new Controller(self::dbStub()))->index()['status']);
    }

    public function testIndexesHeadersMapIsEmpty() {
        $this->assertSame([], (new Controller(self::dbStub()))->index()['headers']);
    }

//--------------------------------------------------------------------------------------------------

    public function testCreateDelegatesToTheDb() {
        $stmt = new \stdClass;

        (new Controller(
            m::mock()
            ->shouldReceive('newCreateStatement')->withNoArgs()->once()->andReturn($stmt)
            ->ordered()
            ->shouldReceive('create')->with($stmt, '{"text":"S12"}', 'S12')->once()
            ->ordered()
            ->getMock()
        ))->create(['text' => 'S12']);
    }

//--------------------------------------------------------------------------------------------------

    private static function dbStub() {
        return m::mock()->shouldIgnoreMissing()->shouldReceive('index')->andReturn([])->getMock();
    }
}