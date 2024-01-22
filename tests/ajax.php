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

switch ($apiCall) {
    case 'contact.retrieve.model':
        $result = $gKSDK->retrieveContactModel();
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
    'payload' => ((isset($_POST['payload'])) ? $_POST['payload'] : null),
    'request' => $result,
));
