<?php

namespace Annotate\Http;

use Guzzle\Http\Client;

/**
 * @group http
 */
class IndexTest extends \PHPUnit_Framework_TestCase {
    public function testHttpStatusIs200() {
        $this->assertSame(200, self::response()->getStatusCode());
    }

    public function testTheResponseBodyIsJsonArray() {
        $body = strval(self::response()->getBody());
        json_decode($body, true);
        $this->assertStringStartsWith('[', $body);
    }

 //--------------------------------------------------------------------------------------------------

    private static function response() {
        return (new Client('http://127.0.0.1/annotator-store-lite'))->get('annotations')->send();
    }
}