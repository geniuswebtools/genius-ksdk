<?php

namespace GeniusKSDK\REST;

class Product extends \GeniusKSDK\REST {

    public function __construct(\GeniusKSDK $client) {
        parent::__construct($client);
    }

    /**
     * List Products
     * https://developer.infusionsoft.com/docs/rest/#tag/Product/operation/listProductsUsingGET
     * 
     * @param array $params
     * @return stdClass Object
     */
    public function list(array $params = null) {
        $httpQuery = $this->buildHTTPQuery($params);
        return $this->client->read('/v1/products' . $httpQuery);
    }

    /**
     * Create a Product
     * https://developer.infusionsoft.com/docs/rest/#tag/Product/operation/createProductUsingPOST
     * 
     * @param array $struct
     * @return stdClass Object
     */
    public function create(array $struct) {
        return $this->client->create('/v1/products', $struct);
    }

    /**
     * Retrieve a Product
     * https://developer.infusionsoft.com/docs/rest/#tag/Product/operation/retrieveProductUsingGET
     * 
     * @param int $id
     * @return stdClass Object
     */
    public function read(int $id) {
        return $this->client->read('/v1/products/' . $id);
    }

    /**
     * Update a Product
     * https://developer.infusionsoft.com/docs/rest/#tag/Product/operation/updateProductUsingPATCH
     * 
     * @param int $id
     * @param array $struct
     * @return stdClass Object
     */
    public function update(int $id, array $struct) {
        return $this->client->update('/v1/products/' . $id, $struct);
    }

    /**
     * Delete a Product
     * https://developer.infusionsoft.com/docs/rest/#tag/Product/operation/deleteProductUsingDELETE
     * 
     * @param int $id
     * @return stdClass Object
     */
    public function delete(int $id) {
        return $this->client->delete('/v1/products/' . $id);
    }
}
