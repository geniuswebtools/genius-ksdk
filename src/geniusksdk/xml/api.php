<?php

namespace GeniusKSDK\XML;

/**
 * This class provides full access to the XML-RPC API.
 */
class API extends \GeniusKSDK\XML {

    public function __construct(\GeniusKSDK $client) {
        parent::__construct($client);
    }

    /**
     * This method provides full access to the  XML-RPC API.
     * 
     * @param string $service
     * @param array $struct
     * @return stdClass Object
     */
    public function call(string $service, array $struct) {
        return $this->send($service, $struct);
    }
}
