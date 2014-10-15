<?php

namespace Spec\CheckoutFinland;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class PaymentSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('CheckoutFinland\Payment');
    }

    function let()
    {
        /**
         *   375917 is the test id for Checkout Finland
         */
        $merchant_id        = "375917";
        /**
         * SAIPPUAKAUPPIAS is the secret for the test merchant
         */
        $merchant_secret    = "SAIPPUAKAUPPIAS";

        $this->beConstructedWith($merchant_id, $merchant_secret);
    }

}
