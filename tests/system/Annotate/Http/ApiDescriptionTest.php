<?php

namespace Annotate\Http;

class ApiDescriptionTest extends \PHPUnit_Framework_TestCase {
    public function testReturnsTheApiName() {
        $this->assertSame('Annotator Store API', self::data()['name']);
    }

    public function testLinksToTheApiDocumentation() {
        $this->assertSame('https://github.com/okfn/annotator/wiki/Storage', self::data()['see']);
    }

    public function testReturnsTheApiVersion() {
        $this->assertRegExp('/^\d\.\d\.\d$/', self::data()['version']);
    }

//--------------------------------------------------------------------------------------------------

    private static function data() {
        return json_decode(file_get_contents('http://127.0.0.1/annotator-store-lite/'), true);
    }
}
