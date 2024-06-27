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
     * List Commissions
     * https://developer.infusionsoft.com/docs/rest/#tag/Affiliate/operation/listCommissionsUsingGET
     * 
     * Retrieve a list of Commissions based on Affiliate or Date Range
     * 
     * List Affiliate Redirects
     * https://developer.infusionsoft.com/docs/rest/#tag/Affiliate/operation/listAffiliateRedirectLinksUsingGET
     * 
     * Retrieves a list of all affiliate redirects
     * 
     * List affiliate summaries
     * https://developer.infusionsoft.com/docs/rest/#tag/Affiliate/operation/listSummariesUsingGET
     * 
     * Retrieve a list of affiliate summaries
     * 
     * List Affiliate clawbacks
     * https://developer.infusionsoft.com/docs/rest/#tag/Affiliate/operation/listAffiliateClawbacksUsingGET
     * 
     * Retrieves a list of all affiliate clawbacks
     * 
     * List Affiliate payments
     * https://developer.infusionsoft.com/docs/rest/#tag/Affiliate/operation/listPaymentsUsingGET
     * 
     * Retrieves a list of all affiliate payments
     * 
     * 
     * @param array $struct
     * @return stdClass Object
     */
    public function list($src, array $struct = null) {
        $httpQuery = $this->buildHTTPQuery($struct);
        '/v1/affiliates';
        '/v1/affiliates/commissions';
        '/v1/affiliates/redirectlinks';
        '/v1/affiliates/summaries';
        '/v1/affiliates/{affiliateId}/clawbacks';
        '/v1/affiliates/{affiliateId}/payments';

        return $this->client->read('/v1/affiliates' . $httpQuery);
    }

    /**
     * Create an affiliate
     * https://developer.keap.com/docs/restv2/#tag/Affiliate/operation/addAffiliateUsingPOST
     * 
     * Create a single affiliate
     * 
     * @param array $struct
     * @return stdClass Object
     */
    public function create(array $struct) {
        $values = $this->restruct($this->defaultStruct(), $struct);
        return $this->client->create('/v2/affiliates', $values);
    }

    /**
     * Retrieve an affiliate 
     * https://developer.keap.com/docs/restv2/#tag/Affiliate/operation/getAffiliateUsingGET_1
     * 
     * Retrieves a single Affiliate
     * 
     * @param int $id
     * @return stdClass Object 
     */
    public function read(int $id) {
        return $this->client->read('/v2/affiliates/' . $id);
    }

    /**
     * Updates a single Affiliate
     * https://developer.keap.com/docs/restv2/#tag/Affiliate/operation/updateAffiliateUsingPATCH
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
     * code string required
     *      The Affiliate code which have some validations.
     * 
     *      The code should not have white spaces
     *      The code should starts with letters
     *      The code minimum 4 characters length
     * 
     * contact_id string required
     *      The contactId identifier , Must be a positive number
     * 
     * name string 
     *      The Affiliate name will be derived from the Contact, when not 
     *      explicitly provided
     * 
     * status string required
     *      Enum: "active" "inactive"
     *      The Affiliate Status
     * 
     * @return array
     */
    public function defaultStruct() {
        return array(
            'code' => '', // required
            'contact_id' => 0, // required
            'name' => '', // The Affiliate name will be derived from the Contact, when not explicitly provided
            'status' => 'active', // "active" "inactive"
        );
    }
}
