<?php

namespace CheckoutFinland;

/**
 * Class Payment
 * @package CheckoutFinland
 */
class Payment
{

    /**
     * @var string Merchant id
     */
    protected $merchant_id;
    /**
     * @var string Secret merchant key
     */
    protected $merchant_secret;

    /**
     * @var string Payment version, currently always '0001'
     */
    protected $version;
    /**
     * @var string Unique identifier for the payment
     */
    protected $stamp;
    /**
     * @var string Amount of payment in cents (10€ == 1000)
     */
    protected $amount;
    /**
     * @var string Reference number for the payment, recommended to be unique but not forced
     */
    protected $reference;
    /**
     * @var string Message/Description for the buyer (Nuts and bolts, Furniture, Cuckoo clocks)
     */
    protected $message;
    /**
     * @var string Language of the payment selection page/bank interface if supported. Currently supported languages include Finnish FI, Swedish SE and English EN
     */
    protected $language;

    /**
     * @var string Url called when returning successfully
     */
    protected $return_url;
    /**
     * @var string Url called when user cancelled payment
     */
    protected $cancel_url;
    /**
     * @var string Url called when payment was rejected (No credit on credit card etc)
     */
    protected $reject_url;
    /**
     * @var string Url called when payment is initially successful but not yet confirmed
     */
    protected $delayed_url;

    /**
     * @var string Country of the buyer, affects available payment methods
     */
    protected $country;
    /**
     * @var string Currency used in payment. Currently only EUR is supported
     */
    protected $currency;
    /**
     * @var string device or method used when creating new transaction. Affects how Checkout servers respond to posting the new payment 1 = HTML 10 = XML
     */
    protected $device;
    /**
     * @var string Payment type or content of purchase. Used to differentiate between adult entertainment and everything else. 1 = normal, 2 = adult entertainment
     */
    protected $content;
    /**
     * @var string Type, currently always 0
     */
    protected $type;
    /**
     * @var string Algorithm used when calculating mac, currently = 3. 1 and 2 are still available but deprecated
     */
    protected $algorithm;

    /**
     * @var DateTime Expected delivery date
     */
    protected $delivery_date;
    /**
     * @var string First name of customer
     */
    protected $first_name;
    /**
     * @var string Last name of customer
     */
    protected $family_name;
    /**
     * @var string Street address of customer
     */
    protected $address;
    /**
     * @var string Postcode of customer
     */
    protected $postcode;
    /**
     * @var string Post office of customer
     */
    protected $post_office;

    /**
     * @param $merchant_id
     * @param $merchant_secret
     */
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

    /**
     * Sets payment/order information
     *
     * @param $stamp
     * @param $amount
     * @param $reference
     * @param $message
     * @param \DateTime $delivery_date
     */
    public function setOrderData($stamp, $amount, $reference, $message, \DateTime $delivery_date)
    {
        $this->stamp            = $stamp;
        $this->amount           = $amount;
        $this->reference        = $reference;
        $this->message          = $message;
        $this->delivery_date    = $delivery_date;
    }

    /**
     * Sets customer information
     *
     * @param $first_name
     * @param $family_name
     * @param $address
     * @param $postcode
     * @param $post_office
     * @param $country
     * @param $language
     */
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

    /**
     * Sets multiple variables at once
     *
     * $example_data =  [
     *   'stamp'             => '1245132',
     *   'amount'            => '1000',
     *   'reference'         => '12344',
     *   'message'           => 'Nuts and bolts',
     *   'delivery_date'     => new \DateTime('2014-12-31'),
     *   'first_name'        => 'John',
     *   'family_name'       => 'Doe',
     *   'address'           => 'Some street 13 B 2',
     *   'postcode'          => '33100',
     *   'post_office'       => 'Some city',
     *   'country'           => 'FIN',
     *   'language'          => 'EN'
     *   ];
     *
     * @param array $params
     */
    public function setData(array $params)
    {
        foreach($params as $key => $value)
        {
            if(property_exists($this, $key)) {
                $this->$key = $value;
            }
        }
    }

    /**
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @param string $address
     */
    public function setAddress($address)
    {
        $this->address = $address;
    }

    /**
     * @return string
     */
    public function getAlgorithm()
    {
        return $this->algorithm;
    }

    /**
     * @param string $algorithm
     */
    public function setAlgorithm($algorithm)
    {
        $this->algorithm = $algorithm;
    }

    /**
     * @return string
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * @param string $amount
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;
    }

    /**
     * @return string
     */
    public function getCancelUrl()
    {
        return $this->cancel_url;
    }

    /**
     * @param string $cancel_url
     */
    public function setCancelUrl($cancel_url)
    {
        $this->cancel_url = $cancel_url;
    }

    /**
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param string $content
     */
    public function setContent($content)
    {
        $this->content = $content;
    }

    /**
     * @return string
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * @param string $country
     */
    public function setCountry($country)
    {
        $this->country = $country;
    }

    /**
     * @return string
     */
    public function getCurrency()
    {
        return $this->currency;
    }

    /**
     * @param string $currency
     */
    public function setCurrency($currency)
    {
        $this->currency = $currency;
    }

    /**
     * @return string
     */
    public function getDelayedUrl()
    {
        return $this->delayed_url;
    }

    /**
     * @param string $delayed_url
     */
    public function setDelayedUrl($delayed_url)
    {
        $this->delayed_url = $delayed_url;
    }

    /**
     * @return DateTime
     */
    public function getDeliveryDate()
    {
        return $this->delivery_date;
    }

    /**
     * @param DateTime $delivery_date
     */
    public function setDeliveryDate($delivery_date)
    {
        $this->delivery_date = $delivery_date;
    }

    /**
     * @return string
     */
    public function getDevice()
    {
        return $this->device;
    }

    /**
     * @param string $device
     */
    public function setDevice($device)
    {
        $this->device = $device;
    }

    /**
     * @return string
     */
    public function getFamilyName()
    {
        return $this->family_name;
    }

    /**
     * @param string $family_name
     */
    public function setFamilyName($family_name)
    {
        $this->family_name = $family_name;
    }

    /**
     * @return string
     */
    public function getFirstName()
    {
        return $this->first_name;
    }

    /**
     * @param string $first_name
     */
    public function setFirstName($first_name)
    {
        $this->first_name = $first_name;
    }

    /**
     * @return string
     */
    public function getLanguage()
    {
        return $this->language;
    }

    /**
     * @param string $language
     */
    public function setLanguage($language)
    {
        $this->language = $language;
    }

    /**
     * @return string
     */
    public function getMerchantId()
    {
        return $this->merchant_id;
    }

    /**
     * @param string $merchant_id
     */
    public function setMerchantId($merchant_id)
    {
        $this->merchant_id = $merchant_id;
    }

    /**
     * @return string
     */
    public function getMerchantSecret()
    {
        return $this->merchant_secret;
    }

    /**
     * @param string $merchant_secret
     */
    public function setMerchantSecret($merchant_secret)
    {
        $this->merchant_secret = $merchant_secret;
    }

    /**
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param string $message
     */
    public function setMessage($message)
    {
        $this->message = $message;
    }

    /**
     * @return string
     */
    public function getPostOffice()
    {
        return $this->post_office;
    }

    /**
     * @param string $post_office
     */
    public function setPostOffice($post_office)
    {
        $this->post_office = $post_office;
    }

    /**
     * @return string
     */
    public function getPostcode()
    {
        return $this->postcode;
    }

    /**
     * @param string $postcode
     */
    public function setPostcode($postcode)
    {
        $this->postcode = $postcode;
    }

    /**
     * @return string
     */
    public function getReference()
    {
        return $this->reference;
    }

    /**
     * @param string $reference
     */
    public function setReference($reference)
    {
        $this->reference = $reference;
    }

    /**
     * @return string
     */
    public function getRejectUrl()
    {
        return $this->reject_url;
    }

    /**
     * @param string $reject_url
     */
    public function setRejectUrl($reject_url)
    {
        $this->reject_url = $reject_url;
    }

    /**
     * @return string
     */
    public function getReturnUrl()
    {
        return $this->return_url;
    }

    /**
     * @param string $return_url
     */
    public function setReturnUrl($return_url)
    {
        $this->return_url = $return_url;
    }

    /**
     * @return string
     */
    public function getStamp()
    {
        return $this->stamp;
    }

    /**
     * @param string $stamp
     */
    public function setStamp($stamp)
    {
        $this->stamp = $stamp;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @return string
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * @param string $version
     */
    public function setVersion($version)
    {
        $this->version = $version;
    }




}
