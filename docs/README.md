> [!IMPORTANT]
> This library is not associated or maintained by Keap, and is an independent 
> project. Please do not contact Keap for support.

# Genius KSDK
This library is designed to work with the Keap REST API using a [Personal Access Token or a Service Account Key.](https://developer.infusionsoft.com/pat-and-sak/)

The library is **not** designed to work with the OAuth2 Authentication.

_Testing proves that this authentication method can also use a Legacy API Key,
but it would be better to use a Service Account Key instead._

## Why use Genius KSDK?
This library makes requests to the Keap REST API by using a Personal Access Token 
or a Service Account Key.  Using one of these two keys means you don't have to 
create a Keap Developer account, and use the OAuth2 authentication implementation.

Here's a quick list of the benefits for using this library to access the Keap
REST API.

- **SUPPORTS** the REST API
- **SUPPORTS** the XML-RPC API (Legacy)
- **CAN** be used with a single access key
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
    $gKSDK = new \GeniusKSDK(array('apiKey' => 'YOUR_KEAP_API_KEY_GOES_HERE'));
} catch (\Exception $ex) {
    echo $ex->getMessage();
    exit;
}

/**
 * Retrieve a Contact Model with the REST or XML APIs... you can also use the 
 * the REST API with generic read() method.
 */
$result = $gKSDK->contact('xml')->model();
$result = $gKSDK->contact()->model();
$result = $gKSDK->read('/v2/contacts/model'); // Generic REST Method
echo '<pre>';
print_r($result);
echo '</pre>';

/**
 * List Contacts
 */
$xmlFilter = array(
    'queryData' => array('Email' => '%@domain.tld'),
    'selectedFields' => array('Id', 'Email'),
    'orderBy' => 'Email',
    'ascending' => false,
);
$restFilter = array(
    'filter' => 'email==dev@domain.tld',
    'order_by' => 'id desc',
    'page_size' => 5,
    'optional_properties' => 'email_addresses,custom_fields,tag_ids',
);
$result = $gKSDK->contact('xml')->list();
$result = $gKSDK->contact('xml')->list($xmlFilter);
$result = $gKSDK->contact()->list();
$result = $gKSDK->contact()->list($restFilter); 
echo '<pre>';
print_r($result);
echo '</pre>';

/**
 * Create Contact
 */
$xmlFields = array(
    'FirstName' => 'Dee',
    'LastName' => 'Veloper',
    'Email' => 'dev@domain.tld',
);
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
$result = $gKSDK->contact('xml')->create($xmlFields); // Add
$result = $gKSDK->contact('xml')->create($xmlFields, 'Email'); // Dupcheck on contact email
$result = $gKSDK->contact()->create($restFields); //  Will add. Does not support dupCheck
echo '<pre>';
print_r($result);
echo '</pre>';

/**
 * List Resthook Events
 */
$result = $gKSDK->resthookEvents();
echo '<pre>';
print_r($result);
echo '</pre>';

/**
 * Create a RESTHook subscription
 * This code is intended to be executed from the hookURL, to automatically 
 * verify itself.
 */
$payload = (object) array(
            'eventKey' => 'order.add',
            'hookUrl' => 'https://domain.tld/path/to/hook/listener}',
);
$result = $gKSDK->createRestHook(json_encode($payload));
echo '<pre>';
print_r($result);
echo '</pre>';
```

If an API helper model like Contact or Resthook hasn't been provided to simplify
API requests, you can use the REST or XML API models to build and execute 
those requests.

```php
require_once '/src/genius-ksdk.php';

try {
    $gKSDK = new \GeniusKSDK(array('apiKey' => 'YOUR_KEAP_API_KEY_GOES_HERE'));
} catch (\Exception $ex) {
    echo $ex->getMessage();
    exit;
}
/**
 * For a XML-RPC API request use the call() method and pass the service, and
 * the correct parameters as an array.
 */
$service = 'ContactService.findByNameOrEmail';
$xmlStruct = array(
    'Dee',
    array('Id', 'FirstName', 'Email'),
    array('Id'),
    true,
    1000,
    0,    
);
$result = $gKSDK->api('xml')->call($service, $xmlStruct);

/**
 * For a RESTful API request use one of the CRUD methods:
 * create(), read(), update() or delete().
 */
$contactId = 123;
$endpoint = '/v1/contacts/' . $contactId;
$result = $gKSDK->api()->read($endpoint);
```

---
Make sure to checkout the [official Keap developer documentation](https://developer.infusionsoft.com/developer-guide/)