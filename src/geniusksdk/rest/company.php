<?php

namespace GeniusKSDK\REST;

class Company extends \GeniusKSDK\REST {

    public function __construct(\GeniusKSDK $client) {
        parent::__construct($client);
    }

    /**
     * List Companies
     * https://developer.infusionsoft.com/docs/restv2/#tag/Company/operation/listCompaniesUsingGET_1
     * 
     * Retrieves a list of all Companies
     * 
     * @param string $payload
     * @return stdClass Object
     */
    public function list(array $params = null) {
        $httpQuery = $this->buildHTTPQuery($params);
        return $this->client->read('/v2/companies' . $httpQuery);
    }

    /**
     * Create a Company
     * https://developer.infusionsoft.com/docs/restv2/#tag/Company/operation/createCompanyUsingPOST_1
     * 
     * Creates a new Company.country_code is required if region is specified.
     * 
     * @param array $struct
     * @return stdClass Object
     */
    public function create(array $struct) {
        return $this->client->create('/v2/companies', $struct);
    }

    /**
     * Retrieve a Company
     * https://developer.infusionsoft.com/docs/restv2/#tag/Company/operation/getCompanyUsingGET_1
     * 
     * Retrieves a single Company
     * 
     * @param int $id
     * @return stdClass Object
     */
    public function read(int $id) {
        return $this->client->read('/v2/companies/' . $id);
    }

    /**
     * Update a Company
     * https://developer.infusionsoft.com/docs/restv2/#tag/Company/operation/patchCompanyUsingPATCH
     * 
     * Updates a Company with the values provided in the request
     * 
     * @param int $id
     * @param array $struct
     * @return stdClass Object
     */
    public function update(int $id, array $struct) {
        return $this->client->update('/v2/companies/' . $id, $struct);
    }

    /**
     * Delete a Company
     * https://developer.infusionsoft.com/docs/restv2/#tag/Company/operation/deleteCompanyUsingDELETE
     * 
     * Deletes the specified Company
     * 
     * @param int $id
     * @return stdClass Object
     */
    public function delete(int $id) {
        return $this->client->delete('' . $id);
    }

    /**
     * List Tagged Companies 
     * https://developer.infusionsoft.com/docs/restv2/#tag/Tags/operation/listCompaniesForTagIdUsingGET_1
     * 
     * Retrieves a list of companies that have the given tag applied To search 
     * for null or empty fields use filter==NONE
     * 
     * @param int $tagId
     * @param array $params
     * @return stdClass Object
     */
    public function listTagged(int $tagId, array $params = null) {
        $httpQuery = $this->buildHTTPQuery($params);
        return $this->client->read('/v2/tags/' . $tagId . '/companies' . $httpQuery);
    }
}
