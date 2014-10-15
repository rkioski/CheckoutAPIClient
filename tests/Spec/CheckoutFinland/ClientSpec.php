<?php

namespace Spec\CheckoutFinland;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ClientSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('CheckoutFinland\Client');
    }
}
