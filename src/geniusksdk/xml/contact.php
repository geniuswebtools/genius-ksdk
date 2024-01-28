<?php

namespace GeniusKSDK\XML;

class Contact extends \GeniusKSDK\XML {

    public function __construct(\GeniusKSDK $client) {
        parent::__construct($client);
    }

    public function model() {
        $params = array_values($this->defaultQueryFilter(array('DataType', 'DefaultValue', 'FormId', 'GroupId', 'Id', 'Label', 'ListRows', 'Name', 'Values')));
        array_unshift($params, 'DataFormField');

        return $this->send('DataService.query', $params);
    }

    public function list(array $struct = null) {
        if ($struct === null) {
            $struct = array();
        }
        $params = array_values($this->restruct($this->defaultQueryFilter(array('Id', 'FirstName', 'LastName')), $struct));
        array_unshift($params, 'Contact');

        return $this->send('DataService.query', $params);
    }

    public function create(array $struct, string $dupCheck = 'Email') {
        $checkOn = array('Email', 'EmailAndName', 'EmailAndNameAndCompany');
        $service = ((in_array($dupCheck, $checkOn, true)) ? 'ContactService.addWithDupCheck' : 'ContactService.add');
        $params = array(
            $struct,
        );
        if ($service === 'ContactService.addWithDupCheck') {
            $params[] = $dupCheck;
        }

        return $this->send($service, $params);
    }

    public function read(int $id, array $struct) {
        $params = array(
            $id,
            $struct,
        );

        return $this->send('ContactService.load', $params);
    }

    public function update(int $id, array $struct) {
        $params = array(
            $id,
            $struct,
        );

        return $this->send('ContactService.update', $params);
    }

    public function delete(int $id) {
        $params = array(
            'Contact',
            $id
        );
        return $this->send('DataService.delete', $params);
    }

    /**
     * Retrieve an Email's Opt-in Status
     * https://developer.infusionsoft.com/docs/xml-rpc/#email-retrieve-an-email-s-opt-in-status
     * 
     * Returns an integer value of 0 for opt out/non-marketable/soft bounce/hard bounce, 1 for single opt-in, or 2 for double opt-in.
     * 
     * @param string $email
     * @return stdClass Object
     */
    public function emailStatus(string $email) {
        $params = array(
            $email
        );
        return $this->send('APIEmailService.getOptStatus', $params);
    }

    /**
     * Add/Remove a tag from a contact
     * https://developer.infusionsoft.com/docs/xml-rpc/#contact-add-a-tag-to-a-contact
     * https://developer.infusionsoft.com/docs/xml-rpc/#contact-remove-a-tag-from-a-contact
     * 
     * @param int $contactId
     * @param int $tagId
     * @param string $context
     * @return stdClass Object
     */
    public function tag(int $contactId, int $tagId, string $context = 'apply') {
        $action = (($context !== 'apply') ? 'removeFromGroup' : 'addToGroup');
        $params = array(
            $contactId,
            $tagId
        );
        return $this->send('ContactService.' . $action, $params);
    }
}
