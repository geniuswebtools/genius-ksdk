<?php

namespace GeniusKSDK\REST;

class BusinessProfile extends \GeniusKSDK\REST {

    public function __construct(\GeniusKSDK $client) {
        parent::__construct($client);
    }

    /**
     * Retrieve business profile
     * https://developer.keap.com/docs/restv2/#tag/Business-Profile/operation/getBusinessProfileUsingGET
     * 
     * Retrieves business profile information.
     * 
     * @return stdClass Object
     */
    public function read() {
        return $this->client->read('/v2/businessProfile');
    }

    /**
     * Updates business profile
     * https://developer.keap.com/docs/restv2/#tag/Business-Profile/operation/patchBusinessProfileUsingPATCH
     * 
     * Updates business profile information.
     * 
     * @param array $struct
     * @return stdClass Object
     */
    public function update(int $id, array $struct) {
        return $this->client->update('/v2/businessProfile/', $struct);
    }
}
