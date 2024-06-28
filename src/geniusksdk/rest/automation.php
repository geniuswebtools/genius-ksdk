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
     * Achieve API Goal
     * https://developer.infusionsoft.com/docs/rest/#tag/Campaign/operation/createAchieveApiGoalEventUsingPOST
     * 
     * Achieves API goal for campaigns with matching integration, callName for a given contactId
     * 
     * @param type $integration
     * @param type $callName
     * @param type $contactId
     * @return stdClass Object
     */
    public function achieveGoal($integration, $callName, int $contactId) {
        return $this->client->create('/v1/campaigns/goals/' . $integration . '/' . $callName, array('contact_id' => $contactId));
    }

    /**
     * Add to Campaign Sequence
     * https://developer.infusionsoft.com/docs/rest/#tag/Campaign/operation/addContactToCampaignSequenceUsingPOST
     * 
     * Adds a single contact to a campaign sequence
     * 
     * Add Multiple to Campaign Sequence
     * https://developer.infusionsoft.com/docs/rest/#tag/Campaign/operation/addContactsToCampaignSequenceUsingPOST
     * 
     * Adds a list of contacts to a campaign sequence Response contains a map of the provided list of Contact Ids related to their individual result.
     * 
     * @param int $campaignId
     * @param int $sequenceId
     * @param array|int $contactIds Array of contact Ids or a single contact Id
     * @return stdClass Object
     */
    public function addToSequence(int $campaignId, int $sequenceId, $contactIds) {
        $endpoint = (( is_scalar($contactIds) ) ? $contactIds : '');
        $payload = (( is_scalar($contactIds) ) ? null : (array) $contactIds);

        return $this->client->create('/v1/campaigns/' . $campaignId . '/sequences/' . $sequenceId . '/contacts/' . $endpoint, $payload);
    }

    /**
     * Remove from Campaign Sequence
     * https://developer.infusionsoft.com/docs/rest/#tag/Campaign/operation/removeContactFromCampaignSequenceUsingDELETE
     * 
     * Removes a single contact from a campaign sequence
     * 
     * Remove Multiple from Campaign Sequence 
     * https://developer.infusionsoft.com/docs/rest/#tag/Campaign/operation/removeContactsFromCampaignSequenceUsingDELETE
     * 
     * Removes a list of contacts from a campaign sequence
     * 
     * @param int $campaignId
     * @param int $sequenceId
     * @param type $contactIds
     * @return stdClass Object
     */
    public function removeFromSequence(int $campaignId, int $sequenceId, $contactIds) {
        $endpoint = (( is_scalar($contactIds) ) ? $contactIds : '');
        $payload = (( is_scalar($contactIds) ) ? null : (array) $contactIds);

        return $this->client->delete('/v1/campaigns/' . $campaignId . '/sequences/' . $sequenceId . '/contacts/' . $endpoint, $payload);
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

    public function create(array $struct) {
        $values = $this->restruct($this->defaultStruct(), $struct);
        return $this->client->create('/v2/affiliates', $values);
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
