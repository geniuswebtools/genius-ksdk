<?php

/**
 * WARNING! YOU SHOULD BE USGIN A SANDBOX ACCOUNT AND NOT A PRODUCTION ACCOUNT
 * FOR THESE TESTS!
 * 
 * This file will run a series of automated tests against the REST & XML-RPC APIs.
 * 
 * Add your Personal Access Token or a Service Account Key in the key.php before 
 * executing the automated tests in the run.php file.
 * 
 */
$apiKey = require_once './key.php';
try {
    TLDR($apiKey);
} catch (\Exception $ex) {
    echo $ex->getMessage();
    exit;
}

include_once '../src/geniusksdk.php';
$gKSDK = new \GeniusKSDK(array('apiKey' => $apiKey));

$test = new \Kellicam();

/**
 * REST contact
 */
$response = $gKSDK->contact()->create(array(
    'given_name' => 'REST',
    'family_name' => 'API',
    'job_title' => 'Test Contact',
    'email_addresses' => array(
        array(
            'email' => 'rest.api.test@domain.tld',
            'field' => 'EMAIL1',
        ),
    ),
        ));
$contactId = (int) $response->content->id;
$pass = ( ($response->header['code'] === '201 Created') && ($contactId > 0) );
$test->expect($pass, 'Wrong response code/No contact Id.', $response);

$response = $gKSDK->contact()->read($contactId);
$pass = ($response->header['code'] === '200 OK');
$test->expect($pass, 'Wrong response code.', $response);

$response = $gKSDK->contact()->update($contactId, array('given_name' => 'REST Updated'));
$pass = ($response->header['code'] === '200 OK');
$test->expect($pass, 'Wrong response code.', $response);

$response = $gKSDK->contact()->delete($contactId);
$pass = ($response->header['code'] === '204 No Content');
$test->expect($pass, 'Wrong response code.', $response);
$contactId = null;


echo $test->results();

