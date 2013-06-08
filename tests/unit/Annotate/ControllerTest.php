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

        m::close();
    }

    public function testCreateReturnsTheJustCreatedObjectWithIdAsTheResponseData() {
        $result = self::c(self::dbStub())->create(['text' => '∞']);
        $this->assertArrayHasKey('data', $result);
        $this->assertSame(['text' => '∞', 'id' => 2112], $result['data']);
    }

    public function testCreateStatusIs200() {
        $this->assertSame(200, self::c(self::dbStub())->create(['text' => '∞'])['status']);
    }

    public function testCreateSendsNoAdditionalHeaders() {
        $this->assertSame(
            [],
            self::c(self::dbStub())->create(['text' => '∞'])['headers']
        );
    }

//--------------------------------------------------------------------------------------------------

    public function testReadDelegatesToTheDb() {
        $stmt = new \stdClass;

        self::c(
            m::mock()
            ->shouldReceive('newReadStatement')->withNoArgs()->once()->andReturn($stmt)
            ->ordered()
            ->shouldReceive('read')->with($stmt, 1650)->once()->andReturn('{}')
            ->ordered()
            ->getMock()
        )->read(1650);
    }

    public function testReadReturnsTheDbDataWithAnIdAdded() {
        $result = self::c(self::dbStub())->read(1709);
        $this->assertArrayHasKey('data', $result);
        $this->assertSame(['mlue' => 42, 'id' => 1709], $result['data']);
    }

    public function testReadStatusIs200() {
        $this->assertSame(200, self::c(self::dbStub())->read(1720)['status']);
    }

    public function testReadSendsNoAdditionalHeaders() {
        $this->assertSame([], self::c(self::dbStub())->read(1721)['headers']);
    }

    public function testReadReturns404IfTheIdIsntFoundInTheDb() {
        $this->assertSame(
            404,

            self::c(
                m::mock()
                ->shouldIgnoreMissing()
                ->shouldReceive('read')->andReturn(false)
                ->getMock()
            )->read(-13)['status']
        );
    }

//--------------------------------------------------------------------------------------------------

    public function testUpdateDelegatesToTheDb() {
        $stmt = new \stdClass;

        self::c(
            m::mock()
            ->shouldReceive('newUpdateStatement')->withNoArgs()->once()->andReturn($stmt)
            ->ordered()
            ->shouldReceive('update')->with($stmt, 1758, '{"text":"S12"}', 'S12')->once()
            ->ordered()
            ->getMock()
        )->update(1758, ['text' => 'S12']);

        m::close();
    }

    public function testUpdateReturnsTheAnnotationDataMergedWithTheIdInThePayload() {
        $this->assertSame(
            ['text' => 'yolo', 'id' => 26],
            self::c(self::dbStub())->update(26, ['text' => 'yolo'])['data']
        );
    }

    public function testUpdateReturnsTheNormalStatusAndNoExtraHeaders() {
        $result = self::c(self::dbStub())->update(1, ['text' => 'ok']);
        $this->assertSame(200, $result['status']);
        $this->assertSame([], $result['headers']);
    }

//--------------------------------------------------------------------------------------------------
    public function testDeleteDelegatesToTheDb() {
        $stmt = new \stdClass;

        self::c(
            m::mock()
            ->shouldReceive('newDeleteStatement')->withNoArgs()->once()->andReturn($stmt)
            ->ordered()
            ->shouldReceive('delete')->with($stmt, 745)->once()
            ->ordered()
            ->getMock()
        )->delete(745);

        m::close();
    }

    public function testDeleteStatusIs204() {
        $this->assertSame(204, self::c(self::dbStub())->delete(754)['status']);
    }

    public function testDeleteReturnsNoExtraHeadersAndTheNullData() {
        $result = self::c(self::dbStub())->delete(758);

        $this->assertSame([], $result['headers']);
        $this->assertSame(null, $result['data']);
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
            ->shouldReceive('read')->andReturn('{"mlue":42}')
            ->getMock();
    }
}
