<?php
error_reporting(E_ALL|E_STRICT);
ini_set('display_errors', 1);

require '../vendor/autoload.php';

use CheckoutFinland\Response;

$demo_merchant_secret   = "SAIPPUAKAUPPIAS";

$response = new Response($demo_merchant_secret);

$response->setRequestParams($_GET);

$status_string = '';

try {
    if($response->validate()) {
        // we have a valid response, now check the status

        // the status codes are listed in the api documentation of Checkout Finland
        switch($response->getStatus())
        {
            case '2':
            case '5':
            case '6':
            case '8':
            case '9':
            case '10':
                // These are paid and we can ship the product
                $status_string = 'PAID';
                break;
            case '7':
            case '3':
            case '4':
                // Payment delayed or it is not known yet if the payment was completed 
                 $status_string = 'DELAYED';
                break;
            case '-1':
                 $status_string = 'CANCELLED BY USER';
                 break;
            case '-2':
            case '-3':
            case '-4':
            case '-10':
                // Cancelled by banks, Checkout Finland, time out e.g. 
                 $status_string = 'CANCELLED';
                break;
        }

    } else {
        // something went wrong with the validation, perhaps the user changed the return parameters
    }
} catch(MacMismatchException $ex) {
    echo 'Mac mismatch';
} catch(UnsupportedAlgorithmException $ex) {
    echo 'Unsupported algorithm';
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
</head>

<body>
    <div style="max-width:800px; margin:auto;">
        <h2>Status of payment: <?php echo $status_string; ?></h2>
        <p><a href="<?php echo "poll.php?STAMP={$_GET['STAMP']}&REFERENCE={$_GET['REFERENCE']}&AMOUNT={$_GET['a']}" ?>">Poll the payment</a></p>
        <p><a href="index.html">Back to cart</a></p>
    </div>
</body>
</html>