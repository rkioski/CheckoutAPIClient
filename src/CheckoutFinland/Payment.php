<?php

namespace CheckoutFinland;

class Payment
{

    protected $merchant_id;
    protected $merchant_secret;

    protected $version;
    protected $stamp;
    protected $amount;
    protected $reference;
    protected $message;
    protected $language;

    protected $return_url;
    protected $cancel_url;
    protected $reject_url;
    protected $delayed_url;

    protected $country;
    protected $currency;
    protected $device;
    protected $content;
    protected $type;
    protected $algorithm;

    protected $delivery_date;
    protected $first_name;
    protected $family_name;
    protected $address;
    protected $postcode;
    protected $post_office;

    public function __construct($merchant_id, $merchant_secret)
    {
        $this->merchant_id      = $merchant_id;
        $this->merchant_secret  = $merchant_secret;

        $this->setDefaultValues();
    }

    private function setDefaultValues()
    {
        $this->version      = '0001';
        $this->device       = '10';
        $this->content      = '1';
        $this->type         = '0';
        $this->algorithm    = '3';
        $this->currency     = 'EUR';
    }

    public function setOrderData($stamp, $amount, $reference, $message, \DateTime $delivery_date)
    {
        $this->stamp            = $stamp;
        $this->amount           = $amount;
        $this->reference        = $reference;
        $this->message          = $message;
        $this->delivery_date    = $delivery_date;
    }

    public function setCustomerData($first_name, $family_name, $address, $postcode, $post_office, $country, $language)
    {
        $this->first_name   = $first_name;
        $this->family_name  = $family_name;
        $this->address      = $address;
        $this->postcode     = $postcode;
        $this->post_office  = $post_office;
        $this->country      = $country;
        $this->language     = $language;
    }

}
