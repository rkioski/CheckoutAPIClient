<?php

namespace Spec\CheckoutFinland;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ResponseSpec extends ObjectBehavior
{

    private $demo_merchant_secret    = "SAIPPUAKAUPPIAS";

    private $valid_response_params = [
        'VERSION'       => '0001',
        'STAMP'         => '1413377065',
        'REFERENCE'     => '12344',
        'PAYMENT'       => '18727244',
        'STATUS'        => '2',
        'ALGORITHM'     => '3',
        'MAC'           => 'BA4D17048431F6ABB22A9B5BEAC6EA10432C7C1B67D295A6808D3D3345CF72B4'
    ];

    private $invalid_response_params = [
        'VERSION'       => '0001',
        'STAMP'         => '666',
        'REFERENCE'     => '12344',
        'PAYMENT'       => '18727244',
        'STATUS'        => '2',
        'ALGORITHM'     => '3',
        'MAC'           => 'BA4D17048431F6ABB22A9B5BEAC6EA10432C7C1B67D295A6808D3D3345CF72B4'
    ];

    function it_is_initializable()
    {
        $this->shouldHaveType('CheckoutFinland\Response');
    }

    function let()
    {
        $this->beConstructedWith($this->demo_merchant_secret);
    }

    function it_can_set_all_variables_from_array()
    {
        $this->setRequestParams($this->valid_response_params);
    }

    function it_returns_true_on_validation_when_given_valid_request_parameters()
    {
        $this->setRequestParams($this->valid_response_params);

        $this->validate()->shouldBe(true);
    }

    function it_throws_mac_mismatch_exception_when_given_invalid_parameters()
    {
        $this->setRequestParams($this->invalid_response_params);

        $this->shouldThrow('CheckoutFinland\Exceptions\MacMismatchException')->duringValidate($this->invalid_response_params);
    }

    function it_throws_unsupported_algorithm_exception_when_using_old_algorithm()
    {
        $params = $this->invalid_response_params;
        $params['ALGORITHM'] = 2;

        $this->setRequestParams($params);

        $this->shouldThrow('CheckoutFinland\Exceptions\UnsupportedAlgorithmException')->duringValidate();
    }

    function its_variables_are_all_accessible_trough_setters_and_getters()
    {
        $this->setAlgorithm(3);
        $this->getAlgorithm()->shouldBe(3);

        $this->setMac('1234567890');
        $this->getMac()->shouldBe('1234567890');

        $this->setMerchantSecret('secret');
        $this->getMerchantSecret()->shouldBe('secret');

        $this->setPayment('1234');
        $this->getPayment()->shouldBe('1234');

        $this->setReference('54321');
        $this->getReference()->shouldBe('54321');

        $this->setStamp('1234');
        $this->getStamp()->shouldBe('1234');

        $this->setStatus(2);
        $this->getStatus()->shouldBe(2);

        $this->setVersion(3);
        $this->getVersion()->shouldBe(3);

    }
}