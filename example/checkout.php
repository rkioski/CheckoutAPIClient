<?php

require '../vendor/autoload.php';

use CheckoutFinland\Payment;
use CheckoutFinland\Client;

$demo_merchant_id       = "375917";
$demo_merchant_secret   = "SAIPPUAKAUPPIAS";
$return_url             = str_replace('checkout.php', 'return.php', $_SERVER['REQUEST_URI']);

$payment = new  Payment($demo_merchant_id, $demo_merchant_secret, $return_url);

$payment_data = [
    'stamp'         => time(),                      // stamp is the unique id for this transaction
    'amount'        => ($_POST['amount'] * 100),    // amount is in cents
    'reference'     => '12345',                     // some reference id (perhaps order id)
    'message'       => 'Nuts and bolts',            // some short description about the order
    'deliveryDate'  => new \DateTime('2014-12-24'), // approximated delivery date, this is shown to customer service in Checkout Finland but not to the buyer
    'firstName'     => $_POST['first-name'],
    'familyName'    => $_POST['last-name'],
    'address'       => $_POST['address'],
    'postOffice'    => $_POST['post-office'],
    'postcode'      => $_POST['zip-code']
];

$payment->setData($payment_data);

$client = new Client();