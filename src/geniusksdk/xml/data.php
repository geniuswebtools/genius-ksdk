<?php

namespace GeniusKSDK\XML;

class Data extends \GeniusKSDK\XML {

    public function __construct(\GeniusKSDK $client) {
        parent::__construct($client);
    }

    /**
     * Query a Data Table
     * https://developer.infusionsoft.com/docs/xml-rpc/#data-query-a-data-table
     * 
     * @param string $table
     * @param array $struct
     * @return stdClass Object
     */
    public function list(string $table, array $struct) {
        $selectFields = (isset($struct['selectedFields'])) ? (array) $struct['selectedFields'] : array();
        $params = array_values($this->defaultQueryFilter($selectFields));
        array_unshift($params, $table);

        return $this->send('DataService.query', $params);
    }

    /**
     * Create a Record
     * https://developer.infusionsoft.com/docs/xml-rpc/#data-create-a-record
     * 
     * @param string $table
     * @param array $struct
     * @return stdClass Object
     */
    public function create(string $table, array $struct) {
        $params = array(
            $table,
            $struct
        );

        return $this->send('DataService.add', $params);
    }

    /**
     * Retrieve a Record
     * https://developer.infusionsoft.com/docs/xml-rpc/#data-retrieve-a-record
     * 
     * @param string $table
     * @param int $id
     * @param array $fields
     * @return stdClass Object
     */
    public function read(string $table, int $id, array $fields) {
        $params = array(
            $table,
            $id,
            $fields
        );

        return $this->send('DataService.load', $params);
    }

    /**
     * Update a Record
     * https://developer.infusionsoft.com/docs/xml-rpc/#data-update-a-record
     * 
     * @param string $table
     * @param int $id
     * @param array $values
     * @return stdClass Object
     */
    public function update(string $table, int $id, array $values) {
        $params = array(
            $table,
            $id,
            $values
        );

        return $this->send('DataService.update', $params);
    }

    /**
     * Delete a Record
     * https://developer.infusionsoft.com/docs/xml-rpc/#data-delete-a-record
     * 
     * @param string $table
     * @param int $id
     * @return stdClass Object
     */
    public function delete(string $table, int $id) {
        $params = array(
            $table,
            $id
        );

        return $this->send('DataService.delete', $params);
    }

    /**
     * Count a Data Table's Records
     * https://developer.infusionsoft.com/docs/xml-rpc/#data-count-a-data-table-s-records
     * 
     * @param string $table
     * @param array $struct
     * @return stdClass Object
     */
    public function count(string $table, array $struct) {
        $params = array(
            $table,
            $struct
        );

        return $this->send('DataService.delete', $params);
    }
}
