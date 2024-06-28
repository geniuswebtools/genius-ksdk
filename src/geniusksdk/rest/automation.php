<?php

namespace GeniusKSDK\REST;

/**
 * Automation
 * https://developer.infusionsoft.com/docs/restv2/#tag/Automation
 * 
 */
class Automation extends \GeniusKSDK\REST {

    public function __construct(\GeniusKSDK $client) {
        parent::__construct($client);
    }

    /**
     * List Automations
     * https://developer.infusionsoft.com/docs/restv2/#tag/Automation/operation/listAutomationsUsingGET
     * 
     * @param string $params
     * @return stdClass Object
     */
    public function list(array $struct = null) {
        $httpQuery = $this->buildHTTPQuery($struct);
        return $this->client->read('/v2/automations' . $httpQuery);
    }

    /**
     * Retrieve an Automation
     * https://developer.infusionsoft.com/docs/restv2/#tag/Automation/operation/getAutomationUsingGET
     * 
     * Retrieves a single automation
     * 
     * @param int $id
     * @param array $struct
     * @return stdClass Object
     */
    public function read(int $id, array $struct = null) {
        $httpQuery = $this->buildHTTPQuery($struct);
        return $this->client->read('/v2/automations/' . $id . $httpQuery);
    }

    /**
     * Update an Automation's Category 
     * https://developer.infusionsoft.com/docs/restv2/#tag/Automation/operation/updateAutomationCategoryUsingPUT
     * 
     * Updates the category of one or more automations
     * 
     * @param array $struct
     * @return stdClass Object
     */
    public function update(array $struct) {
        return $this->client->update('/v2/automations/category', $struct, 'PUT');
    }

    /**
     * Delete an Automation
     * https://developer.infusionsoft.com/docs/restv2/#tag/Automation/operation/deleteAutomationUsingDELETE
     * 
     * Deletes a single automation
     * 
     * @param array $ids
     * @return stdClass Object
     */
    public function delete(array $struct) {
        return $this->client->delete('/v2/automations', $struct);
    }
}
