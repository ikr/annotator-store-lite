<?php

namespace Annotate\Http;

use Guzzle\Http\Client;

class CreateTest extends \PHPUnit_Framework_TestCase {
    public function testCreatedAnnotationAppearsInTheInfdex() {
        $resp = (new Client('http://127.0.0.1'))->post(
            '/annotations',
            null,
            json_encode(self::newAnnotationData())
        )->send();

        $this->assertGreaterThan(0, intval($resp->json()['id']));
    }

//--------------------------------------------------------------------------------------------------

    private static function newAnnotationData() {
        return [
            'text' => 'Eyes, look your last. Arms, take your last embrace',
            'more' => ', and lips, O you The doors of breath, seal with a righteous kiss. A dateless bargain to engrossing death.'
        ];
    }
}