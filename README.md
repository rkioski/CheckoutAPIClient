CheckoutAPIClient
=================

[![Build Status](https://travis-ci.org/rkioski/CheckoutAPIClient.svg?branch=master)](https://travis-ci.org/rkioski/CheckoutAPIClient) [![Latest Stable Version](https://poser.pugx.org/rkioski/checkout-finland-api-client/v/stable.svg)](https://packagist.org/packages/rkioski/checkout-finland-api-client) [![Latest Unstable Version](https://poser.pugx.org/rkioski/checkout-finland-api-client/v/unstable.svg)](https://packagist.org/packages/rkioski/checkout-finland-api-client) [![License](https://poser.pugx.org/rkioski/checkout-finland-api-client/license.svg)](https://packagist.org/packages/rkioski/checkout-finland-api-client)


API client for the finnish payment gateway Checkout Finland.

## Requirements

The following versions of PHP are supported:

* PHP 5.4
* PHP 5.5
* PHP 5.6
* HHVM
* cURL or allow_url_fopen = On in php.ini

Client will try to send an HTTP POST by using PHP stream context with file_get_contents() or if that fails it will check if cURL extension is installed and try to use that instead.


# Installation

Via composer
```
composer require rkioski/checkout-finland-api-client dev-master
```

# Usage

Take a look at the example folder for a full working example.

Creating payment
```php
require 'vendor/autoload.php';

use CheckoutFinland\Payment;
use CheckoutFinland\Client;

$demo_merchant_id       = "375917";
$demo_merchant_secret   = "SAIPPUAKAUPPIAS";
$return_url             = "http://yourservice.com/your_return_handling_script.php";

$payment = new  Payment($demo_merchant_id, $demo_merchant_secret);

$payment->setUrls($return_url);

$stamp          = time(); // unique id for the payment  
$amount         = '1000'; // amount is in cents
$reference      = '12344';
$message        = 'Nuts and bolts';
$delivery_date  = new \DateTime('2014-12-31');

$first_name     = 'John';
$family_name    = 'Doe';
$address        = 'Some Street 13 B 2';
$postcode       = '33100';
$post_office    = 'Some city';
$country        = 'FIN';
$language       = 'EN';

$payment->setOrderData($stamp, $amount, $reference, $message, $delivery_date);
$payment->setCustomerData($first_name, $family_name, $address, $postcode, $post_office, $country, $language);

$client = new Client();

$response = $client->sendPayment($payment);

$response_xml = @simplexml_load_string($response);
// redirect to payment page
header('Location: '.$response_xml->paymentURL);

// or show the payment buttons on your webpage, you will find all the data you need in the response xml
```

Handling return
```php
require 'vendor/autoload.php';

use CheckoutFinland\Response;

$demo_merchant_secret   = "SAIPPUAKAUPPIAS";

$response = new Response($demo_merchant_secret);

$response->setRequestParams($_GET);

if($response->validate()) 
{
    // now check the status parameter if the payment was paid and do whatever you do in your shop when you get money
}
```
