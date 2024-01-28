<?php

namespace GeniusKSDK\REST;

/**
 * Tags
 * https://developer.infusionsoft.com/docs/restv2/#tag/Tags
 */
class Tag extends \GeniusKSDK\REST {

    public function __construct(\GeniusKSDK $client) {
        parent::__construct($client);
    }

    /**
     * List Tags
     * https://developer.infusionsoft.com/docs/restv2/#tag/Settings/operation/getContactOptionTypesUsingGET_1
     * 
     * Retrieve a list of tags defined in the application. To search for null or 
     * empty fields use filter==NONE
     * 
     * @param array $params
     * @return stdClass Object
     */
    public function list(array $params = null) {
        $httpQuery = $this->buildHTTPQuery($params);
        return $this->client->read('/v2/tags' . $httpQuery);
    }

    /**
     * Create Tag
     * https://developer.infusionsoft.com/docs/restv2/#tag/Tags/operation/createTagUsingPOST_1
     * 
     * Create a new tag
     * 
     * @param array $struct
     * @return stdClass Object
     */
    public function create(array $struct) {
        $payload = json_encode($struct);
        return $this->client->create('/v2/tags', $payload);
    }

    /**
     * Retrieve a Tag
     * https://developer.infusionsoft.com/docs/restv2/#tag/Tags/operation/getTagUsingGET_1
     * 
     * Returns the tag with the specified tagId
     * 
     * @param int $id
     * @return stdClass Object
     */
    public function read(int $id) {
        return $this->client->read('/v2/tags/' . $id);
    }

    /**
     * Update a Tag
     * https://developer.infusionsoft.com/docs/restv2/#tag/Tags/operation/patchTagUsingPATCH
     * 
     * Updates a tag with only the values provided in the request
     * 
     * @param int $id
     * @param array $struct
     * @return stdClass Object
     */
    public function update(int $id, array $struct) {
        $payload = json_encode($struct);
        return $this->client->update('/v2/tags/' . $id, $payload);
    }

    /**
     * Delete Tag
     * https://developer.infusionsoft.com/docs/restv2/#tag/Tags/operation/deleteTagUsingDELETE
     * 
     * Delete Tag by tag id
     * 
     * @param int $id
     * @return stdClass Object
     */
    public function delete(int $id) {
        return $this->client->delete('/v2/tags/' . $id);
    }

    /**
     * List Tag Categories
     * https://developer.infusionsoft.com/docs/restv2/#tag/Tags/operation/listTagCategoriesUsingGET
     * 
     * Retrieve a list of tag categories defined in the application To search 
     * for null or empty fields use filter==NONE
     * 
     * @param array $params
     * @return stdClass Object
     */
    public function listTagCategories(array $params = null) {
        $httpQuery = $this->buildHTTPQuery($params);
        return $this->client->read('/v2/tags/categories' . $httpQuery);
    }

    /**
     * Create Tag Category
     * https://developer.infusionsoft.com/docs/restv2/#tag/Tags/operation/createTagCategoryUsingPOST_1
     * 
     * Create a new tag category
     * 
     * @param string $payload
     * @return stdClass Object
     */
    public function createTagCategory(string $payload) {
        return $this->client->create('/v2/tags/categories', $payload);
    }

    /**
     * Retrieve a Tag Category
     * https://developer.infusionsoft.com/docs/restv2/#tag/Tags/operation/getCategoryUsingGET
     * 
     * Returns the tag category with the specified category_id
     * 
     * @param int $id
     * @return stdClass Object
     */
    public function getTagCategory(int $id) {
        return $this->client->read('/v2/tags/categories/' . $id);
    }

    /**
     * Delete Tag Category
     * https://developer.infusionsoft.com/docs/restv2/#tag/Tags/operation/deleteTagCategoryUsingDELETE
     * 
     * Deletes the specified Tag Category
     * 
     * @param int $id
     * @return stdClass Object
     */
    public function deleteTagCategory(int $id) {
        return $this->client->delete('/v2/tags/categories/' . $id);
    }

    /**
     * Update a Tag Category
     * https://developer.infusionsoft.com/docs/restv2/#tag/Tags/operation/patchTagCategoryUsingPATCH
     * 
     * Updates a tag category with only the values provided in the request
     * 
     * @param int $id
     * @param string $payload
     * @return stdClass Object
     */
    public function updateTagCategory(int $id, string $payload) {
        return $this->client->update('/v2/tags/categories/' . $id, $payload);
    }
}
