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
        return $this->client->update('/v2/tags/' . $id, $struct);
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
}
