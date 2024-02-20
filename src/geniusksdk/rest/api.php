<?php

namespace GeniusKSDK\REST;

/**
 * This class provides full access to the REST API.
 */
class API extends \GeniusKSDK\REST {

    public function __construct(\GeniusKSDK $client) {
        parent::__construct($client);
    }

    /**
     * Alias of read() for consistency in other REST class models.
     * 
     * @param string $path
     * @return stdClass Object
     */
    public function list(string $path) {
        return $this->read($path);
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
     * @return stdClass Object
     */
    public function update(string $path, array $struct) {
        $payload = json_encode($struct);
        return $this->client->request($path, array(
                    'method' => 'PATCH',
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
