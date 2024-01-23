> [!IMPORTANT]
> This library is not associated or maintained by Keap, and is an independent 
> project. Please do not contact Keap for support.

# Genius KSDK
This library is designed to work with the Keap REST API using a Personal Access 
Token or a Service Account Key.

The library is **not** designed to work with the OAuth2 Authentication.

_Testing proves that this authentication method can also use a Legacy API Key,
but it would be better to use a Service Account Key instead._

## Why use Genius KSDK?
This library makes requests to the Keap REST API by using a Personal Access Token 
or a Service Account Key.  Using one of these two keys means you don't have to 
create a Keap Developer account, and use the OAuth2 authentication implementation.

Here's a quick list of the benefits for using this library to access the Keap
REST API.

- Does **NOT** require creating a Keap developer app
- Does **NOT** require OAuth2 authentication
- Does **NOT** require Composer
- **CAN** be used with a single access key

## Why not use Genius KSDK?
Rarly is any thing perfect, and this code is no exception.

- Does **NOT** work with the Legacy XML-RPC API

## Usage
Load the library into your PHP code:

```php
require_once '/src/genius-ksdk.php';

try {
    $gKSDK = new \GeniusKSDK(array('apiKey' => 'YOUR_KEAP_API_KEY_GOES_HERE'));
} catch (\Exception $ex) {
    echo $ex->getMessage();
    exit;
}

$hookEvents = $gKSDK->resthookEvents();
echo '<pre>';
print_r($hookEvents);
echo '</pre>';
```

---
Make sure to checkout the [official Keap developer documentation](https://developer.infusionsoft.com/developer-guide/)