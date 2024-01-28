<?php

namespace GeniusKSDK\REST;

/**
 * Tag Category
 * https://developer.infusionsoft.com/docs/restv2/#tag/Tags
 */
class TagCategory extends \GeniusKSDK\REST {

    public function __construct(\GeniusKSDK $client) {
        parent::__construct($client);
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
    public function list(array $params = null) {
        $httpQuery = $this->buildHTTPQuery($params);
        return $this->client->read('/v2/tags/categories' . $httpQuery);
    }

    /**
     * Create Tag Category
     * https://developer.infusionsoft.com/docs/restv2/#tag/Tags/operation/createTagCategoryUsingPOST_1
     * 
     * Create a new tag category
     * 
     * @param array $struct
     * @return stdClass Object
     */
    public function create(array $struct) {
        $payload = json_encode($struct);
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
    public function read(int $id) {
        return $this->client->read('/v2/tags/categories/' . $id);
    }

    /**
     * Update a Tag Category
     * https://developer.infusionsoft.com/docs/restv2/#tag/Tags/operation/patchTagCategoryUsingPATCH
     * 
     * Updates a tag category with only the values provided in the request
     * 
     * @param int $id
     * @param array $struct
     * @return stdClass Object
     */
    public function update(int $id, array $struct) {
        $payload = json_encode($struct);
        return $this->client->update('/v2/tags/categories/' . $id, $payload);
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
    public function delete(int $id) {
        return $this->client->delete('/v2/tags/categories/' . $id);
    }
}
