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
        return [
            'status' => 200,
            'headers' => [],

            'data' => array_merge(
                $annotationData,

                ['id' => $this->db->create(
                    $this->db->newCreateStatement(),
                    json_encode($annotationData),
                    $annotationData['text']
                )]
            )
        ];
    }

    public function read($id) {
        return [
            'status' => 200,
            'headers' => [],

            'data' => array_merge(
                json_decode($this->db->read($this->db->newReadStatement(), $id), true),
                ['id' => $id]
            )
        ];
    }

    public function update($id, $annotationData) {
        $this->db->update(
            $this->db->newUpdateStatement(),
            $id,
            json_encode($annotationData),
            $annotationData['text']
        );

        return [
            'status' => 200,
            'headers' => [],
            'data' => array_merge($annotationData, ['id' => $id])
        ];
    }

    public function delete($id) {
        $this->db->delete($this->db->newDeleteStatement(), $id);

        return [
            'status' => 204,
            'headers' => [],
            'data' => null
        ];
    }
}
