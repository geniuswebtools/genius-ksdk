<?php

namespace GeniusKSDK\REST;

class Contact extends \GeniusKSDK\REST {

    public function __construct(\GeniusKSDK $client) {
        parent::__construct($client);
    }

    /**
     * Retrieve Contact Model 
     * https://developer.infusionsoft.com/docs/restv2/#tag/Contact/operation/retrieveContactModelUsingGET_1
     * 
     * Get the custom fields and optional properties for the Contact object
     * 
     * @return stdClass Object
     */
    public function model() {
        return $this->client->read('/v2/contacts/model');
    }

    /**
     * List Contacts
     * https://developer.infusionsoft.com/docs/restv2/#tag/Contact/operation/listContactsUsingGET_1
     * 
     * Retrieves a list of contacts
     * https://integration.keap.com/t/rest-v2/85756/3
     * 
     * @param string $params
     * @return stdClass Object
     */
    public function list(array $struct = null) {
        $httpQuery = $this->buildHTTPQuery($struct);
        return $this->client->read('/v2/contacts' . $httpQuery);
    }

    /**
     * Create a Contact
     * https://developer.infusionsoft.com/docs/restv2/#tag/Contact/operation/createContactUsingPOST_1
     * 
     * Note: Contact must contain at least one item in email_addresses or 
     * phone_numbers and country_code is required if region is specified.
     * 
     * @param array $payload
     * @return stdClass Object
     */
    public function create(array $struct) {
        return $this->client->create('/v2/contacts', $struct);
    }

    /**
     * Retrieve a Contact
     * https://developer.keap.com/docs/restv2/#tag/Contact/operation/getContactUsingGET_1
     * 
     * $fields = Comma-delimited list of Contact properties to include in the response. 
     * Available fields are: 
     *  score_value, addresses, anniversary, birthday, company, contact_type, 
     *  custom_fields, create_time, email_addresses, fax_numbers, job_title, 
     *  update_date, leadsource_id, middle_name, origin, owner_id, phone_numbers, 
     *  preferred_locale, preferred_name,prefix, relationships, social_accounts, 
     *  source_type, spouse_name, suffix, time_zone,website, tag_ids, utm_parameters
     * 
     * @param int $id
     * @param string $fields
     * @return stdClass Object 
     */
    public function read(int $id, array $struct = null) {
        $httpQuery = $this->buildHTTPQuery($struct);
        $endpoint = '/v2/contacts/' . $id . $httpQuery;
        return $this->client->read($endpoint);
    }

    /**
     * Update a Contact
     * https://developer.keap.com/docs/restv2/#tag/Contact/operation/patchContactUsingPATCH
     * 
     * @param int $id
     * @param array $struct
     * @return stdClass Object
     */
    public function update(int $id, array $struct) {
        return $this->client->update('/v2/contacts/' . $id, $struct);
    }

    /**
     * Delete a Contact
     * https://developer.infusionsoft.com/docs/restv2/#tag/Contact/operation/deleteContactUsingDELETE_1
     * 
     * Deletes the specified contact.
     * 
     * @param int $id
     * @return stdClass Object
     */
    public function delete(int $id) {
        return $this->client->delete('/v2/contacts/' . $id);
    }

    /**
     * Email Address
     * https://developer.infusionsoft.com/docs/restv2/#tag/Email-Address
     * 
     * Retrieve an Email Address status
     * 
     * @param string $email
     * @return stdClass Object
     */
    public function emailStatus(string $email) {
        return $this->client->read('/v2/emailAddresses/' . $email);
    }

    /**
     * Add/Remove a tag from a contact
     * https://developer.keap.com/docs/restv2/#tag/Tags/operation/applyTagsUsingPOST
     * https://developer.keap.com/docs/restv2/#tag/Tags/operation/removeTagsUsingPOST
     * 
     * @param int $contactId
     * @param int $tagId
     * @param string $context
     * @return stdClass Object
     */
    public function tag(int $contactId, int $tagId, string $context = 'apply') {
        $action = (($context !== 'apply') ? 'removeTags' : 'applyTags');
        return $this->create('/v2/tags/' . $tagId . '/contacts:' . $action, array('contact_ids' => array($contactId)));
    }

    /**
     * Retrieve a list of contacts with specific tag
     * https://developer.infusionsoft.com/docs/restv2/#tag/Tags/operation/listContactsWithTagIdUsingGET
     * 
     * Retrieve a list of contacts To search for null or empty fields use filter==NONE
     * 
     * @param int $tagId
     * @param array $params
     * @return stdClass Object
     */
    public function listWithTag(int $tagId, array $params = null) {
        $httpQuery = $this->buildHTTPQuery($params);
        return $this->client->read('/v2/tags/' . $tagId . '/contacts' . $httpQuery);
    }
}
