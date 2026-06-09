<?php

namespace GeniusKSDK\REST;

/**
 * This class provides access to the REST API using basic CRUD operations.
 * 
 * This class DOES NOT enforce a REST base URL.
 */
class API extends \GeniusKSDK\REST\V2 {

    public function __construct(\GeniusKSDK $client) {
        parent::__construct($client);
    }

    /**
     * Create an object
     * 
     * @param string $path
     * @param array $struct
     * @return stdClass Object
     */
    public function create(string $path, array $struct) {
        $payload = json_encode($struct);
        return $this->client->request($path, array(
                    'method' => 'POST',
                    'header' => array('Content-Type: application/json'),
                    'content' => $payload,
        ));
    }

    /**
     * Read an object or Retrieve a list of objects
     * 
     * @param string $path
     * @return stdClass Object
     */
    public function read(string $path) {
        return $this->client->request($path);
    }

    /**
     * Update an object
     * 
     * @param string $path
     * @param array $struct
     * @param string $method
     * @return stdClass Object
     */
    public function update(string $path, array $struct, string $method = 'PATCH') {
        $payload = json_encode($struct);
        $useMethod = strtoupper($method);
        return $this->client->request($path, array(
                    'method' => (($useMethod !== 'PATCH') ? $useMethod : 'PATCH'),
                    'header' => array('Content-Type: application/json'),
                    'content' => $payload,
        ));
    }

    /**
     * Delete an object
     * 
     * @param string $path
     * @return stdClass Object
     */
    public function delete(string $path) {
        return $this->client->request($path, array(
                    'method' => 'DELETE',
        ));
    }
}
