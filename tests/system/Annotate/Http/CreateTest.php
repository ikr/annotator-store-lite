<?php

namespace Annotate\Http;

use Guzzle\Http\Client;

class CreateTest extends \PHPUnit_Framework_TestCase {
    public function testCreatedAnnotationCanBeFetchedListedAndDeleted() {
        $client = new Client('http://127.0.0.1');

        $resp = $client->post(
            '/annotations',
            null,
            json_encode(self::newAnnotationData())
        )->send();

        $this->assertSame(200, $resp->getStatusCode());

        $id = intval($resp->json()['id']);
        $this->assertGreaterThan(0, $id);

        $this->assertSame(200, $client->get("/annotations/$id")->send()->getStatusCode());
        $this->assertSame($id, intval($client->get("/annotations/$id")->send()->json()['id']));
    }

//--------------------------------------------------------------------------------------------------

    private static function newAnnotationData() {
        return [
            'text' => 'Eyes, look your last. Arms, take your last embrace',
            'more' => ', and lips, O you The doors of breath, seal with a righteous kiss. A dateless bargain to engrossing death.'
        ];
    }
}