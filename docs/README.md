> [!IMPORTANT]
> This library is not associated or maintained by Keap, and is an independent 
> project. Please do not contact Keap for support.

# Genius KSDK
This library is designed to work with the Keap XML-RPC legacy API and the REST 
APIs using a [Personal Access Token or a Service Account Key.](https://developer.infusionsoft.com/pat-and-sak/)

The library is intended for 1st party integrations working directly with the owner 
of the Keap Account. The libary **not** designed to work with the OAuth2 
Authentication. If you are looking to create 3rd party apps you shoule use the
OAuth2 implementation and the Official Keap API.

> [!IMPORTANT]
>_Sunsetting of the Legacy API Keys has begun. Brownouts using the Legacy API keys 
>will start on Aug 5, 2024 and access removed on Oct 31, 2024._

## Why use Genius KSDK?
This library makes requests to the Keap XML-RPC and REST API by using a Personal 
Access Token or a Service Account Key.  Using one of these two keys means you 
don't have to create a Keap Developer account and use the OAuth2 authentication 
implementation for 1st party integrations.

Here's a quick list of the benefits for using this library:

- **SUPPORTS** the REST API V1 & V2 endpoints
- **SUPPORTS** the XML-RPC API (Legacy)
- **CAN** be used with a PAT or SAK
- **USE** the API that matches your clients need
---
- Does **NOT** require a Keap developer app
- Does **NOT** support OAuth2 authentication
- Does **NOT** support Composer

## What you need to know
Where possible _and applicable_, I have tried to keep the methods between the two
API types: REST and XML-RPC, similar.  However, they're not completely interchangable.

The APIs vary between the parameters required for certain filters, and even though
I've used as many of the V2 REST API endpoints, the V1 endpoints use different
structs for the filter content.

Searching and filter options appear to be more limited with the REST API than the 
XML-RPC DataService.query.  If you're looking for specific records you may want to 
use the call() method in the XML\API class model to build your query. An example 
is provided on how to use the call() method.

**RESTHooks** are only supported via the REST API.

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

This library will make requests to the REST API by default.  If you need to use 
the XML-RPC API, you will need to be explicit in your request to use the 'xml' API.

```php
/**
 * Requests use the REST API by default. 
 * You must explcitly use the XML-RPC API.
 */
$gKSDK->contact() // Implicit REST usage by default
$gKSDK->contact('rest') // Explicit REST usage
$gKSDK->contact('xml') // Implicit XML-RPC usage

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

$result = $gKSDK->contact('xml')->list();
echo '<pre>' . print_r($result, 1) . '</pre>';

$xmlFilter = array(
    'queryData' => array('Email' => '%@domain.tld'),
    'selectedFields' => array('Id', 'Email'),
    'orderBy' => 'Email',
    'ascending' => false,
);
$result = $gKSDK->contact('xml')->list($xmlFilter); // XML-RPC with filter
echo '<pre>' . print_r($result, 1) . '</pre>';

/**
 * Retrieve a contact model
 * 
 * This is an example where a similar method was created for the XML-RPC API 
 * because there was a comparable request available.
 */
$result = $gKSDK->contact()->model();
echo '<pre>' . print_r($result, 1) . '</pre>';

$result = $gKSDK->contact('xml')->model();
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

$result = $gKSDK->contact()->create($restFields); // Will ALWAYS add. Does not support dupCheck
echo '<pre>' . print_r($result, 1) . '</pre>';

$xmlFields = array(
    'FirstName' => 'John',
    'LastName' => 'Doe',
    'Email' => 'j.doe@domain.tld',
);
$result = $gKSDK->contact('xml')->create($xmlFields); // Will Add
$result = $gKSDK->contact('xml')->create($xmlFields, 'Email'); // Add or Update with dupcheck on contact email
echo '<pre>' . print_r($result, 1) . '</pre>';

/**
 * List Resthook Events 
 * 
 * REST API ONLY!
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

For a generic XML-RPC request, use the XML api('xml')->call() method. You can 
execute any request supported by the XML-RPC API by passing the service name and 
an array of parameters supported by the requested service.

**Do NOT** pass the privateKey parameter! The privateKey will be prepended to the 
parameters array when the call() method is executed.

```php
/**
 * For a generic XML-RPC API request use the call() method.  Provide the service, 
 * and the corresponding service parameters as an array.
 */
$service = 'ContactService.findByNameOrEmail';
$xmlStruct = array(
    'John',
    array('Id', 'FirstName', 'Email'),
    array('Id'),
    true,
    1000,
    0,    
);
$result = $gKSDK->api('xml')->call($service, $xmlStruct);
echo '<pre>' . print_r($result, 1) . '</pre>';
```

For a generic RESTful API request use one of the available CRUD methods: create(),
read(), update() or delete().  This is useful if a class model or specific methods 
have not been provided in the library due to oversight, intent or newly added endpoints.

```php
/**
 * Generic REST Create Request
 * Create a tag
 */
$endpoint = '/v2/tags';
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
$endpoint = '/v1/contacts/' . $contactId;
$result = $gKSDK->api()->read($endpoint);
echo '<pre>' . print_r($result, 1) . '</pre>';

/**
 * Generic REST Update Request: 
 * Update a product record
 */
$productId = 456;
$endpoint = '/v1/products/' . $productId;
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
$endpoint = '/v1/products/' . $productId . '/image';
$result = $gKSDK->api()->delete($endpoint);
echo '<pre>' . print_r($result, 1) . '</pre>';
```


---
Make sure to checkout the [official Keap developer documentation](https://developer.infusionsoft.com/developer-guide/)