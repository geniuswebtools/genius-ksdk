<?php

namespace GeniusKSDK\REST;

/**
 * Automation Category
 * https://developer.keap.com/docs/restv2/#tag/AutomationCategory
 */
class AutomationCategory extends \GeniusKSDK\REST {

    public function __construct(\GeniusKSDK $client) {
        parent::__construct($client);
    }

    /**
     * List automation categories
     * https://developer.keap.com/docs/restv2/#tag/AutomationCategory/operation/listCategoriesUsingGET
     * 
     * Lists all automation categories based on the request parameters
     * 
     * @param array $params
     * @return stdClass Object
     */
    public function list(array $params = null) {
        $httpQuery = $this->buildHTTPQuery($params);
        return $this->client->read('/v2/automationCategory' . $httpQuery);
    }

    /**
     * Create automation category
     * https://developer.keap.com/docs/restv2/#tag/AutomationCategory/operation/createCategoryUsingPOST
     * 
     * Creates a single automation category
     * 
     * @param array $struct
     * @return stdClass Object
     */
    public function create(array $struct) {
        return $this->client->create('/v2/tags/categories', $struct);
    }

    /**
     * Save automation category
     * https://developer.keap.com/docs/restv2/#tag/AutomationCategory/operation/saveCategoryUsingPUT
     * 
     * Creates or updates a single automation category
     * 
     * @param array $struct
     * @return stdClass Object
     */
    public function update(array $struct) {
        return $this->client->update('/rest/v2/automationCategory', $struct, 'PUT');
    }

    /**
     * Delete automation category
     * https://developer.keap.com/docs/restv2/#tag/AutomationCategory/operation/deleteCategoriesUsingDELETE
     * 
     * Deletes one or more automation categories based on the request parameters
     * 
     * @param array $ids
     * @return stdClass Object
     */
    public function delete(array $ids) {
        $httpQuery = $this->buildHTTPQuery(array('ids' => $ids));
        return $this->client->delete('/v2/automationCategory' . $httpQuery);
    }
}
