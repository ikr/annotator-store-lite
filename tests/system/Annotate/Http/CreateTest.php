<?php

namespace Annotate\Http;

use Guzzle\Http\Client;
use Guzzle\Http\Exception\ClientErrorResponseException;

class CreateTest extends \PHPUnit_Framework_TestCase {
    public function testCreatedAnnotationCanBeFetchedUpdatedListedAndDeleted() {
        $client = new Client('http://127.0.0.1/annotator-store-lite');

        $resp = $client->post(
            'annotations',
            null,
            json_encode(self::newAnnotationData())
        )->send();

        $this->assertSame(200, $resp->getStatusCode());

        $id = intval($resp->json()['id']);
        $this->assertGreaterThan(0, $id);

        $this->assertSame(200, $client->get("annotations/$id")->send()->getStatusCode());
        $this->assertSame($id, intval($client->get("annotations/$id")->send()->json()['id']));

        $client->put("annotations/$id", null, '{"text":"lala"}')->send();
        $this->assertSame('lala', $client->get("annotations/$id")->send()->json()['text']);

        $this->assertSame(204, $client->delete("annotations/$id")->send()->getStatusCode());

        try {
            $client->get("annotations/$id")->send()->getStatusCode();
        }
        catch (ClientErrorResponseException $ex) {
            $this->assertSame(404, $ex->getResponse()->getStatusCode());
            return;
        }

        $this->fail('Expected ClientErrorResponseException');
    }

//--------------------------------------------------------------------------------------------------

    private static function newAnnotationData() {
        return [
            'text' => 'Eyes, look your last. Arms, take your last embrace',
            'more' => ', and lips, O you The doors of breath, seal with a righteous kiss. A dateless bargain to engrossing death.'
        ];
    }
}
