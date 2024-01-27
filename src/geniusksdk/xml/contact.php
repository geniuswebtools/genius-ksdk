<?php

namespace GeniusKSDK\XML;

class Contact extends \GeniusKSDK\XML {

    public function __construct(\GeniusKSDK $client) {
        parent::__construct($client);
    }

    public function model() {
        $params = array_values($this->defaultListFilter(array('DataType', 'DefaultValue', 'FormId', 'GroupId', 'Id', 'Label', 'ListRows', 'Name', 'Values')));
        array_unshift($params, 'DataFormField');

        return $this->send('DataService.query', $params);
    }

    public function list(array $struct = null) {
        if ($struct === null) {
            $struct = array();
        }
        $params = array_values($this->restruct($this->defaultListFilter(), $struct));
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

    protected function defaultListFilter(array $selectedFields = array('Id', 'FirstName', 'LastName')) {
        return array(
            'limit' => 1000,
            'page' => 0,
            'queryData' => array('Id' => '%'),
            'selectedFields' => $selectedFields,
            'orderBy' => 'Id',
            'ascending' => true,
        );
    }
}
