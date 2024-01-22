<?php

/**
 * This file is only intended to be used with the Genius KSDK testing suite.
 */
require_once './../src/geniusksdk.php';

if ('POST' !== getenv('REQUEST_METHOD')) {
    header('HTTP/1.1 405 Method Not Allowed');
    header('Content-Type: application/json');
    echo json_encode(array(
        'code' => 405,
        'error' => 'method not allowed',
    ));
    exit;
}

$apiKey = htmlentities(filter_input(INPUT_POST, 'apiKey'), ENT_QUOTES);
$apiCall = htmlentities(filter_input(INPUT_POST, 'apiCall'), ENT_QUOTES);
if (empty($apiKey)) {
    header('HTTP/1.1 400 Bad Request');
    header('Content-Type: application/json');
    echo json_encode(array(
        'code' => 400,
        'error' => 'API Key cannot be empty!',
    ));
    exit;
}

try {
    $gKSDK = new \GeniusKSDK(array('apiKey' => $apiKey));
} catch (\Exception $ex) {
    header('HTTP/1.1 409 Conflict');
    header('Content-Type: application/json');
    echo json_encode(array(
        'code' => 409,
        'error' => $ex->getMessage(),
    ));
    exit;
}

$payload = ((isset($_POST['payload'])) ? $_POST['payload'] : null);

switch ($apiCall) {
    // Contacts
    case 'contact.retrieve.model':
        $result = $gKSDK->retrieveContactModel();
        break;
    case 'contact.list':
//        $params = array(
//            'filter' => 'email==genius@a2m3.com',
//            'order_by' => 'id desc',
//            'page_size' => 1000,
//            'optional_properties' => 'email_addresses,custom_fields,tag_ids',
//        );
        $params = $payload;
        $result = $gKSDK->findContacts($payload);
        $payload = (($params !== null) ? '?' . http_build_query((array) $params, '', '&', PHP_QUERY_RFC3986) : '');
        break;
    // Resthooks
    case 'resthook.list.event.types':
        $result = $gKSDK->resthookEvents();
        break;
    case 'resthook.list.stored.hooks':
        $result = $gKSDK->resthooks();
        break;
    default:
        $result = array(
            'code' => 200,
            'error' => 'Undefined api call requested.',
        );
        break;
}

/**
 * Disable caching of the current document:
 */
header('Cache-Control: no-cache, no-store, max-age=0, must-revalidate');
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
header('Pragma: no-cache');

header('Content-Type: application/json');
echo json_encode(array(
    'payload' => $payload,
    'request' => $result,
));
