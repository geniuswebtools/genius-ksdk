<?php

namespace GeniusKSDK\XML;

class TagCategory extends \GeniusKSDK\XML {

    private $table = 'ContactGroupCategory';

    public function __construct(\GeniusKSDK $client) {
        parent::__construct($client);
    }

    public function list(array $struct = null) {
        if ($struct === null) {
            $struct = array();
        }
        $params = array_values($this->restruct($this->defaultQueryFilter(array('Id', 'CategoryName', 'CategoryDescription')), $struct));
        array_unshift($params, 'Contact');

        return $this->send('DataService.query', $params);
    }

    public function create(array $struct) {
        $params = array(
            $this->table,
            $this->restruct($this->defaultTagStruct(), $struct)
        );
        return $this->send('DataService.add', $params);
    }

    public function read(int $id) {
        $params = array(
            $this->table,
            $id,
            array_unshift($this->defaultTagStruct(), 'Id'),
        );
        return $this->send('DataService.load', $params);
    }

    public function update(int $id, array $struct) {
        $params = array(
            $this->table,
            $id,
            $struct
        );
        return $this->send('DataService.update', $params);
    }

    public function delete(int $id) {
        $params = array(
            $this->table,
            $id,
        );
        return $this->send('DataService.delete', $params);
    }

    protected function defaultTagStruct() {
        return array(
            'CategoryName' => '',
            'CategoryDescription' => 'Added via API using GeniusKSDK'
        );
    }
}
