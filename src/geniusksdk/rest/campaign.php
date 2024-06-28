<?php

namespace GeniusKSDK\REST;

/**
 * REST V1 
 * @link https://developer.infusionsoft.com/docs/rest/#tag/Campaign REST V1 documentation
 * 
 * REST V2
 * @link https://developer.infusionsoft.com/docs/restv2/#tag/Campaign REST V2 documentation
 */
class Campaign extends \GeniusKSDK\REST {

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
     * Add Contacts to Campaign Sequence
     * https://developer.infusionsoft.com/docs/restv2/#tag/Campaign/operation/addContactsToCampaignSequenceUsingPOST_1
     * 
     * Adds a list of contacts to a campaign sequence Response contains a map of the provided list of Contact Ids related to their individual result.
     * 
     * @param int $campaignId
     * @param int $sequenceId
     * @param array $struct 
     * @return stdClass Object
     */
    public function addToSequence(int $campaignId, int $sequenceId, array $struct) {
        $payload = $this->restruct(array('contact_ids' => array()), $struct);
        return $this->client->create('/v2/campaigns/' . $campaignId . '/sequences/' . $sequenceId . ':addContacts', $payload);
    }

    /**
     * Remove Contacts from Campaign Sequence
     * https://developer.infusionsoft.com/docs/restv2/#tag/Campaign/operation/removeContactsFromCampaignSequenceUsingPOST
     * 
     * Removes a list of contacts from a campaign sequence Response contains a map of the provided list of Contact Ids related to their individual result.
     * 
     * @param int $campaignId
     * @param int $sequenceId
     * @param array $struct 
     * @return stdClass Object
     */
    public function removeFromSequence(int $campaignId, int $sequenceId, $struct) {
        $payload = $this->restruct(array('contact_ids' => array()), $struct);
        return $this->client->create('/v2/campaigns/' . $campaignId . '/sequences/' . $sequenceId . ':removeContacts', $payload);
    }

    /**
     * List Campaigns
     * https://developer.infusionsoft.com/docs/restv2/#tag/Campaign/operation/listCampaignsUsingGET_1
     * 
     * Retrieves all campaigns for the authenticated user
     * 
     * @param array $struct
     * @return stdClass Object
     */
    public function list(array $struct = null) {
        $httpQuery = $this->buildHTTPQuery($struct);
        return $this->client->read('/v2/campaigns' . $httpQuery);
    }

    /**
     * Retrieve a Campaign
     * https://developer.infusionsoft.com/docs/restv2/#tag/Campaign/operation/getCampaignUsingGET_1
     * 
     * Retrieves a single campaign
     * 
     * @param int $id
     * @param array $struct
     * @return stdClass Object
     */
    public function read(int $id) {
        return $this->client->read('/v2/campaigns/' . $id);
    }
}
