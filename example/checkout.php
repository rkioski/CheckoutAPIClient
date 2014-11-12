<?php
error_reporting(E_ALL|E_STRICT);
ini_set('display_errors', 1);

require '../vendor/autoload.php';

use CheckoutFinland\Payment;
use CheckoutFinland\Client;

$demo_merchant_id       = "375917";
$demo_merchant_secret   = "SAIPPUAKAUPPIAS";
$return_url             = 'http://' .$_SERVER['SERVER_NAME'] .str_replace('checkout.php', 'return.php', $_SERVER['REQUEST_URI']);

$payment = new  Payment($demo_merchant_id, $demo_merchant_secret);
$payment->setUrls($return_url);

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
    'postcode'      => $_POST['zip-code'],
    'country'       => 'FIN',                       // country affects what payment options are shown FIN = all, others = credit cards
    'language'      => 'EN'
];

$payment->setData($payment_data);

$client = new Client();

$response = $client->sendPayment($payment);

if($response)
{
    $xml = @simplexml_load_string($response); // use @ to suppress warnings, checkout finland responds with an error string instead of xml if something went wrong

    if($xml and isset($xml->id)) {
        // now we have a proper response xml and can show payment options to customer

        // here you can pass the xml to your view for rendering or something else
        // we just render the payment options a bit further down this file

             
    } else  { 
        // something went wrong, check merchant id and secret and after that every other parameter
        // do some error handling
        var_dump($response);
    }
} 
else {
    // no response at all, maybe the server is down, do some error handling
} 
?>
<!doctype html>

<html lang="en">
<head>
    <link rel="stylesheet" href="http://yui.yahooapis.com/pure/0.5.0/pure-min.css">
    <!--[if lte IE 8]>
        <link rel="stylesheet" href="http://yui.yahooapis.com/pure/0.5.0/grids-responsive-old-ie-min.css">
    <![endif]-->
    <!--[if gt IE 8]><!-->
        <link rel="stylesheet" href="http://yui.yahooapis.com/pure/0.5.0/grids-responsive-min.css">
    <!--<![endif]-->

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Checkout Finland API example</title>

    <!-- Some styling for the payment buttons -->
    <style>
        .C1 {
             width: 180px;
             height: 120px;
             border: 1pt solid #a0a0a0;
             display: block;
             float: left;
             margin: 7px;
             -moz-border-radius: 5px; -webkit-border-radius: 5px; border-radius: 5px;
             clear: none;
             padding: 0;
            }
        .C1:hover {
             background-color: #f0f0f0;
             border-color: black;
            }
        .C1 form {
             width: 180px;height: 120px;
            }
        .C1 form span {
             display:table-cell; vertical-align:middle;
             height: 92px;
             width: 180px;
            }
        .C1 form span input {
             margin-left: auto;
             margin-right: auto;
             display: block;
             border: 1pt solid #f2f2f2;
             -moz-border-radius: 5px; -webkit-border-radius: 5px; border-radius: 5px;
             padding: 5px;
             background-color: white;
            }
        .C1:hover form span input {
             border: 1pt solid black;
            }
        .C1 div {
             text-align: center;
             font-family: arial;
             font-size: 8pt;
            }
    </style>
</head>

<body>
    <div style="clear:both; display:block; max-width:800px; margin:auto;">
        <h2>Payment options:</h2>

        <?php 
        if($xml and isset($xml->id))
        {
            $html = '<div class="block" style="padding: 10px; background-color: white;">';

            foreach($xml->payments->payment->banks as $bankX) 
            {
                foreach($bankX as $bank) 
                {
                    $html .= "<div class='C1' style='float: left; margin-right: 20px; min-height: 100px;' text-align: center;><form action='{$bank['url']}' method='post'><p>\n";
                    foreach($bank as $key => $value) 
                    {
                        $html .= "<input type='hidden' name='$key' value='$value' />\n";
                    }
                    $html .= "<span><input type='image' src='{$bank['icon']}' /></span><div><p>{$bank['name']}</p></div></form></div>\n";
                }
            }
        }
        echo "<div>$html<div style='clear:both;'></div></div>";
        ?>
    </div>

    <div style="clear:both; margin-top:15px; display:block;">
        <p>The payment options listed here are in test mode, some options are missing (like credit cards) 
            that will be shown on this list when you use production credentials. For testing purposes the easiest 
            payment method is Nordea: login credentials are prefilled with demo credentials and you can use whatever 
            string you wish as the authorization code (Vahvistustunnus in finnish)</p>

        <p>If you can't show the payment buttons on your page you can also redirect the customer to payment
            page hosted by Checkout Finland by using 'paymentURL' element from the xml. This is a direct link that you
            can share with the customer for later use (send in an email e.g.).</p>
        <p><a href="<?php echo $xml->paymentURL; ?>"><?php echo $xml->paymentURL; ?></a></p>
    </div>

    <div>

    </div>
</body>
</html>