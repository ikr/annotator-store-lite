<?php

namespace Annotate;

class Controller {
    private $db;

    public function __construct($db) {
        $this->db = $db;
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
        $this->db->create(
            $this->db->newCreateStatement(),
            json_encode($annotationData),
            $annotationData['text']
        );
    }
}