<?php

namespace Annotate\Http;

use Guzzle\Http\Client;

class IndexTest extends \PHPUnit_Framework_TestCase {
    public function testHttpStatusIs200() {
        $this->assertSame(200, self::response()->getStatusCode());
    }

 //--------------------------------------------------------------------------------------------------

    private static function response() {
        return (new Client('http://127.0.0.1'))->get('/annotations')->send();
    }
}