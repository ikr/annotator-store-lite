<?php

namespace Annotate;

class Controller {
    private $db;
    private $apiRootUrlWithoutTrailingSlash;

    public function __construct($db, $apiRootUrlWithoutTrailingSlash) {
        $this->db = $db;
        $this->apiRootUrlWithoutTrailingSlash = $apiRootUrlWithoutTrailingSlash;
    }

    public function index() {
        return [
            'status' => 200,
            'headers' => [],

            'data' => array_map(
                function ($row) {
                    return array_merge(
                        json_decode($row['json'], true),
                        ['id' => $row['id']]
                    );
                },

                $this->db->index($this->db->newIndexStatement())
            )
        ];
    }

    public function create($annotationData) {
        $id = $this->db->create(
            $this->db->newCreateStatement(),
            json_encode($annotationData),
            $annotationData['text']
        );

        return [
            'status' => 303,

            'headers' => [
                'Location' => implode(
                    '/',
                    [$this->apiRootUrlWithoutTrailingSlash, 'annotations', $id]
                )
            ],

            'data' => null
        ];
    }
}
