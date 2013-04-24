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

    public function testIndexStatusIsNormally200() {
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

    public function testCreateReturnsNullAsItsResponseData() {
        $result = self::c(self::dbStub())->create(['text' => '∞']);

        $this->assertArrayHasKey('data', $result);
        $this->assertNull($result['data']);
    }

    public function testCreateStatusIs303() {
        $this->assertSame(303, self::c(self::dbStub())->create(['text' => '∞'])['status']);
    }

    public function testCreateSendsTheLocationHeaderPointingToTheJustCreatedResource() {
        $this->assertSame(
            ['Location' => 'http://example.com/foo/annotations/2112'],
            self::c(self::dbStub())->create(['text' => '∞'])['headers']
        );
    }

//--------------------------------------------------------------------------------------------------

    private static function c($db) {
        return new Controller($db, 'http://example.com/foo');
    }

    private static function dbStub() {
        return m::mock()
            ->shouldIgnoreMissing()
            ->shouldReceive('index')->andReturn([])
            ->shouldReceive('create')->andReturn(2112)
            ->getMock();
    }
}
