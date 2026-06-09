> [!IMPORTANT]
> This library is not associated or maintained by Keap, and is an independent 
> project. Please do not contact Keap for support.

# Genius KSDK
This library is designed to work with the Keap REST v2 APIs using a 
[Personal Access Token or a Service Account Key.](https://developer.infusionsoft.com/pat-and-sak/)

The library is intended for 1st party integrations working directly with the owner 
of the Keap Account. This libary is **not** designed to work with the OAuth2 
Authentication. If you are looking to create 3rd party apps you should use the
OAuth2 implementation and the Official Keap API.

> [!IMPORTANT]
>_The Keap REST v1 API has been deprecated._

## Why use Genius KSDK?
This library makes requests to the Keap REST v2 API by using a Personal 
Access Token or a Service Account Key.  Using one of these two keys means you 
don't have to create a Keap Developer account and use the OAuth2 authentication 
implementation for 1st party integrations.

Here's a quick list of the benefits for using this library:

- **SUPPORTS** the REST v2 API endpoints
- **CAN** be used with a PAT or SAK
---
- Does **NOT** require a Keap developer app
- Does **NOT** support OAuth2 authentication
- Does **NOT** support Composer

## Usage
Load the library into your PHP code.  The built in autoloader will handle loading 
any additional classes and traits.

```php
require_once '/src/genius-ksdk.php';

try {
    $gKSDK = new \GeniusKSDK(array('apiKey' => 'YOUR_KEAP_PAT_or_SAK_GOES_HERE'));
} catch (\Exception $ex) {
    echo $ex->getMessage();
    exit;
}
```

```php

/**
 * List contact examples
 */
$result = $gKSDK->contact()->list();
echo '<pre>' . print_r($result, 1) . '</pre>';

$restFilter = array(
    'filter' => 'email==dev@domain.tld',
    'order_by' => 'id desc',
    'page_size' => 5,
    'optional_properties' => 'email_addresses,custom_fields,tag_ids',
);
$result = $gKSDK->contact()->list($restFilter); // REST with filter
echo '<pre>' . print_r($result, 1) . '</pre>';

/**
 * Create a contact
 */
$restFields = array(
    'given_name' => 'Dee',
    'family_name' => 'Veloper',
    'job_title' => 'Developer',
    'email_addresses' => array(
        array(
            'email' => 'dev@domain.tld',
            'field' => 'EMAIL1',
            'opt_in_reason' => 'Convincing opt in reason goes here'
        ),
    ),
);

/**
 * List Resthook Events 
 */
$result = $gKSDK->resthook()->events();
echo '<pre>' . print_r($result, 1) . '</pre>';

/**
 * Create a RESTHook subscription
 * This code is intended to be executed from the hookURL, to automatically 
 * verify itself.
 */
$payload = (object) array(
            'eventKey' => 'order.add',
            'hookUrl' => 'https://domain.tld/path/to/hook/listener}',
);
$result = $gKSDK->resthook()->create($payload);
echo '<pre>' . print_r($result, 1) . '</pre>';
```

For a generic RESTful API request use one of the approriate CRUD methods: create(),
read(), update() or delete().  This is useful if a class model or specific methods 
have not been provided in the library due to oversight, intent, newly added endpoints
or poor Keap developer documentation.

```php
/**
 * Generic REST Create Request
 * Create a tag
 */
$endpoint = '/tags';
$createStruct = array(
    'description' => 'Added using generic REST API request.',
    'name' => 'Developer'
);
$result = $gKSDK->api()->create($endpoint, $createStruct);
echo '<pre>' . print_r($result, 1) . '</pre>';

/**
 * Generic REST Read Request: 
 * Read a contact record
 */
$contactId = 123;
$endpoint = '/contacts/' . $contactId;
$result = $gKSDK->api()->read($endpoint);
echo '<pre>' . print_r($result, 1) . '</pre>';

/**
 * Generic REST Update Request: 
 * Update a product record
 */
$productId = 456;
$endpoint = '/products/' . $productId;
$productStruct = array(
    'product_name' => 'Awesome Sauce',
    'product_price' => 99.99,
);
$result = $gKSDK->api()->update($endpoint, $productStruct);
echo '<pre>' . print_r($result, 1) . '</pre>';

/**
 * Generic Delete Request: 
 * Delete a product image
 */
$productId = 456;
$endpoint = '/products/' . $productId . '/image';
$result = $gKSDK->api()->delete($endpoint);
echo '<pre>' . print_r($result, 1) . '</pre>';
```


---
Make sure to checkout the [official Keap developer documentation](https://developer.infusionsoft.com/developer-guide/)
Review the Keap [API Token Quota and Usage Measurements](https://developer.infusionsoft.com/api-token-quota-and-usage-measurements/)