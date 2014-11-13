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

Client will try to send an HTTP POST by using PHP stream context with file_get_contents() or if that fails it will check if cURL is installed and try to use that instead.


# Installation

Via composer
```
composer require rkioski/checkout-finland-api-client dev-master
```

# Usage

Take a look at the example folder.