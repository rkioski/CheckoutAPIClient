<?php

namespace CheckoutFinland;

class Payment
{

    protected $merchant_id;
    protected $merchant_secret;

    public function __construct($merchant_id, $merchant_secret)
    {
        $this->merchant_id      = $merchant_id;
        $this->merchant_secret  = $merchant_secret;
    }
}
