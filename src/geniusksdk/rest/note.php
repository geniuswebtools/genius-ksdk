<?php

namespace GeniusKSDK\REST;

class Note extends \GeniusKSDK\REST {

    public function __construct(\GeniusKSDK $client) {
        parent::__construct($client);
    }

    /**
     * List Notes
     * https://developer.infusionsoft.com/docs/restv2/#tag/Note/operation/listNotesUsingGET_1
     * 
     * Retrieves a list of notes
     * 
     * @param int $contactId
     * @param array $params
     * @return stdClass Object
     */
    public function list(int $contactId, array $params = null) {
        $httpQuery = $this->buildHTTPQuery($params);
        return $this->client->read('/v2/contacts/' . $contactId . '/notes' . $httpQuery);
    }

    /**
     * Create a Note
     * https://developer.infusionsoft.com/docs/restv2/#tag/Note/operation/createNoteUsingPOST_1
     * 
     * Creates a new note as the authenticated user. Either a "title" or "body" is required
     * 
     * @param int $contactId
     * @param array $struct
     * @return stdClass Object
     */
    public function create(int $contactId, array $struct) {
        return $this->client->create('https://api.infusionsoft.com/crm/rest/v2/contacts/' . $contactId . '/notes', $struct);
    }

    /**
     * Get a Note
     * https://developer.infusionsoft.com/docs/restv2/#tag/Note/operation/getNoteUsingGET_1
     * 
     * Get the specified note
     * 
     * @param int $contactId
     * @param int $noteId
     * @return stdClass Object
     */
    public function read(int $contactId, int $noteId) {
        return $this->client->read('/v2/contacts/' . $contactId, '/notes/' . $noteId);
    }

    /**
     * Update a note
     * https://developer.infusionsoft.com/docs/restv2/#tag/Note/operation/updateNoteUsingPATCH
     * 
     * Update a note for a contact
     * 
     * @param int $contactId
     * @param int $noteId
     * @param array $struct
     * @return stdClass Object
     */
    public function update(int $contactId, int $noteId, array $struct) {
        return $this->client->update('/v2/contacts/' . $contactId . '/notes/' . $noteId, $struct);
    }

    /**
     * Delete a Note
     * https://developer.infusionsoft.com/docs/restv2/#tag/Note/operation/deleteNoteUsingDELETE_1
     * 
     * Deletes the specified note
     * 
     * @param int $contactId
     * @param int $noteId
     * @return stdClass Object
     */
    public function delete(int $contactId, int $noteId) {
        return $this->client->delete('/v2/contacts/' . $contactId . '/notes/' . $noteId);
    }
}
