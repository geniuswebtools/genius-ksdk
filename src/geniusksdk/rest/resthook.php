<?php

namespace GeniusKSDK\REST;

class RESTHook extends \GeniusKSDK\REST {

    public function __construct(\GeniusKSDK $client) {
        parent::__construct($client);
    }

    /**
     * List Hook Event Types
     * List the available types of Events that can be listened to
     * https://developer.infusionsoft.com/docs/rest/#tag/REST-Hooks/operation/list_hook_event_types
     * 
     * @return stdClass Object
     */
    public function events() {
        return $this->client->read('/v1/hooks/event_keys');
    }

    /**
     * List Stored Hook Subscriptions
     * Lists your hook subscriptions
     * https://developer.infusionsoft.com/docs/rest/#tag/REST-Hooks/operation/list_stored_hook_subscriptions
     * 
     * @return stdClass Object
     */
    public function list() {
        return $this->client->read('/v1/hooks');
    }

    /**
     * Create a Hook Subscription
     * https://developer.infusionsoft.com/docs/rest/#tag/REST-Hooks/operation/create_a_hook_subscription
     * 
     * Payload: 
     * {
     *   "eventKey": "string",
     *   "hookUrl": "string"
     * }
     * 
     * @param array $struct
     * @param bool $verify Automatically verify the resthook subscription.
     * @return stdClass Object
     */
    public function create(array $struct, bool $verify = true) {
        if ($verify === true) {
            $this->verify();
        }
        $payload = json_encode($struct);
        return $this->client->create('/v1/hooks', $payload);
    }

    /**
     * Retrieve a Hook Subscription
     * https://developer.infusionsoft.com/docs/rest/#tag/REST-Hooks/operation/retrieve_a_hook_subscription
     * 
     * Retrieves an existing hook subscription and its status.
     * 
     * If your hook subscription becomes inactive, you may request an activation 
     * attempt via Verify a Hook Subscription.
     * 
     * @param int $key
     * @return stdClass Object
     */
    public function read(int $key) {
        return $this->client->read('/v1/hooks/' . $key);
    }

    /**
     * Delete a Hook Subscription
     * https://developer.infusionsoft.com/docs/rest/#tag/REST-Hooks/operation/delete_a_hook_subscription
     * 
     * Stop receiving hooks by deleting an existing hook subscription.
     * 
     * @param int $key
     * @return stdClass Object
     */
    public function delete(int $key) {
        return $this->client->delete('/v1/hooks/' . $key);
    }

    /**
     * Verify a Hook Subscription
     * 
     * @return mixed null|string
     */
    public function verify() {
        if (getenv('REQUEST_METHOD') !== 'POST') {
            return;
        }
        $header = $this->requestHeaders();
        $XHookSecret = ((isset($header['X-Hook-Secret'])) ? $header['X-Hook-Secret'] : null);
        if ($XHookSecret === null) {
            return;
        }
        header('X-Hook-Secret: ' . $XHookSecret);
        echo $this->requestBody();
        exit;
    }
}
