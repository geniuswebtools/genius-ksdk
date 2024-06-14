<?php

namespace GeniusKSDK\REST;

class AffiliateProgram extends \GeniusKSDK\REST {

    public function __construct(\GeniusKSDK $client) {
        parent::__construct($client);
    }

    /**
     * List Commission Programs
     * https://developer.keap.com/docs/rest/#tag/Affiliate/operation/listProgramsUsingGET
     * 
     * @param array $struct
     * @return stdClass Object
     */
    public function list(array $struct = null) {
        $httpQuery = $this->buildHTTPQuery($struct);
        return $this->client->read('/v1/affiliates/programs' . $httpQuery);
    }

    /**
     * 
     * 
     * @param array $struct
     * @return stdClass Object
     */
    public function create(array $struct) {
//        $values = $this->restruct($this->defaultStruct(), $struct);
//        return $this->client->create('/v2/affiliates', $values);
    }

    /**
     * 
     * 
     * @param int $id
     * @return stdClass Object 
     */
    public function read(int $id) {
//        return $this->client->read('/v2/affiliates/' . $id);
    }

    /**
     * Update a Affiliate Commission Program
     * https://developer.keap.com/docs/restv2/#tag/Affiliate/operation/patchCommissionProgramUsingPATCH
     * 
     * @param int $id
     * @param array $struct
     * @param array $mask An optional list of properties to be updated. If set, only the provided properties will be updated and others will be skipped.
     * @return stdClass Object
     */
    public function update(int $id, array $struct, array $mask = null) {
        $httpQuery = (($mask !== null) ? $this->buildHTTPQuery($mask) : '');
        return $this->client->update('/v2/affiliates/commissionPrograms/' . $id . $httpQuery, $struct);
    }

    /**
     * 
     * 
     * @param int $id
     * @return stdClass Object
     */
    public function delete(int $id) {
//        return $this->client->delete('/v2/affiliates/' . $id);
    }

    /**
     * @return array
     */
    public function defaultStruct() {
        return array(
//            'code' => '', // required
//            'contact_id' => 0, // required
//            'name' => '', // The Affiliate name will be derived from the Contact, when not explicitly provided
//            'status' => 'active', // "active" "inactive"
        );
    }
}
