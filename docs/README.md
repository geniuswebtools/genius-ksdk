> [!IMPORTANT]
> This class is not maintained by Keap, and is an independent project.

# Genius KSDK
This class is designed to work with the Keap REST API using a Personal Access 
Token or a Service Account Key.

The class is **not** designed to work with the OAuth2 Authentication.

_Testing proves that this authentication method can also use a Legacy API Key,
but it would be better to use a Service Account Key._

##Why use this PHP class?
Here's a quick list of the benefits for using this PHP class to access the Keap
REST API.

- Does **NOT** require creating a Keap developer app
- Does **NOT** require OAuth2 authentication
- Does **NOT** require Composer
- **CAN** be used with a single access key

##Usage##
Load the class into your PHP code:

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