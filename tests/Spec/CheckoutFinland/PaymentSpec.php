<?php

namespace Spec\CheckoutFinland;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class PaymentSpec extends ObjectBehavior
{
    /**
     * @var string 375917 is the test id for Checkout Finland
     */
    private $demo_merchant_id       = "375917";

    /**
     * @var string SAIPPUAKAUPPIAS is the secret for the test merchant
     */
    private $demo_merchant_secret   = "SAIPPUAKAUPPIAS";

    function it_is_initializable()
    {
        $this->shouldHaveType('CheckoutFinland\Payment');
    }

    function let()
    {
        $this->beConstructedWith($this->demo_merchant_id, $this->demo_merchant_secret);
    }

    function it_stores_order_data()
    {
        date_default_timezone_set('Europe/Helsinki');

        $stamp          = '1245132';
        $amount         = '1000';
        $reference      = '12344';
        $message        = 'Nuts and bolts';
        $delivery_date  = new \DateTime('2014-12-31');

        $this->setOrderData($stamp, $amount, $reference, $message, $delivery_date);
    }

    function it_stores_customer_data()
    {

        $first_name     = 'John';
        $family_name    = 'Doe';
        $address        = 'Some Street 13 B 2';
        $postcode       = '33100';
        $post_office    = 'Some city';
        $country        = 'FIN';
        $language       = 'EN';

        $this->setCustomerData($first_name, $family_name, $address, $postcode, $post_office, $country, $language);
    }

}
