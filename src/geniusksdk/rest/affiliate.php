<?php

namespace GeniusKSDK\REST;

class Affiliate extends \GeniusKSDK\REST {

    public function __construct(\GeniusKSDK $client) {
        parent::__construct($client);
    }

    /**
     * Retrieve Affiliate Model
     * https://developer.infusionsoft.com/docs/rest/#tag/Affiliate/operation/retrieveAffiliateModelUsingGET
     * 
     * Get the custom fields for the Affiliate object
     * 
     * @return stdClass Object
     */
    public function model() {
        return $this->client->read('/v1/affiliates/model');
    }

    /**
     * List Affiliates
     * https://developer.infusionsoft.com/docs/rest/#tag/Affiliate/operation/listAffiliatesUsingGET
     * 
     * Retrieves a list of all affiliates
     * 
     * @param array $struct
     * @return stdClass Object
     */
    public function list(array $struct = null) {
        $httpQuery = $this->buildHTTPQuery($struct);
        return $this->client->read('/v1/affiliates' . $httpQuery);
    }

    /**
     * Create an affiliate
     * https://developer.infusionsoft.com/docs/rest/#tag/Affiliate/operation/createAffiliateUsingPOST
     * 
     * Create a single affiliate
     * 
     * @param array $struct
     * @return stdClass Object
     */
    public function create(array $struct) {
        $values = $this->restruct($this->defaultStruct(), $struct);
        return $this->client->create('/v1/affiliates', $values);
    }

    /**
     * Retrieve an affiliate 
     * https://developer.infusionsoft.com/docs/rest/#tag/Affiliate/operation/getAffiliateUsingGET
     * 
     * Retrieve a single affiliate
     * 
     * @param int $id
     * @return stdClass Object 
     */
    public function read(int $id) {
        return $this->client->read('/v1/affiliates/' . $id);
    }

    /**
     * Retrieve an affiliate 
     * 
     * @param int $id
     * @param array $struct
     * @return stdClass Object
     */
    public function update(int $id, array $struct) {
        return $this->client->update('/v2/affiliates/' . $id, $struct);
    }

    /**
     * Delete an affiliate
     * 
     * @param int $id
     * @return stdClass Object
     */
    public function delete(int $id) {
        return $this->client->delete('/v2/affiliates/' . $id);
    }

    /**
     * status [active|inactive]
     * 
     * @return array
     */
    public function defaultStruct() {
        return array(
            'code' => '', // required
            'contact_id' => 0, // required
            'password' => '', // required
            'parent_id' => 0,
            'name' => '',
            'notify_on_lead' => false,
            'notify_on_sale' => false,
            'status' => 'active',
            'track_leads_for' => 0,
        );
    }
}
