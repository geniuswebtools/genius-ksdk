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
     * Create an affiliate program is not supported
     * 
     * 
     * @param array $struct
     * @throws \Exception
     */
    public function create(array $struct) {
        throw new \Exception(sprintf(self::EX_UNSUPPORTED, 'Create'));
    }

    /**
     * Read an affiliate program is not supported
     * 
     * @param int $id
     * @throws \Exception
     */
    public function read(int $id) {
        throw new \Exception(sprintf(self::EX_UNSUPPORTED, 'Read'));
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
     * Delete an affiliate program is not supported
     * 
     * @param int $id
     * @throws \Exception
     */
    public function delete(int $id) {
        throw new \Exception(sprintf(self::EX_UNSUPPORTED, 'Delete'));
    }
}
