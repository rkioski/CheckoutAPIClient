<?php

namespace CheckoutFinland;
use CheckoutFinland\Exceptions\AmountTooLargeException;
use CheckoutFinland\Exceptions\AmountUnderMinimumException;
use CheckoutFinland\Exceptions\UrlTooLongException;

/**
 * Class Payment
 * @package CheckoutFinland
 */
class Payment
{

    /**
     * @var string Merchant id (AN 20)
     */
    protected $merchant_id;
    /**
     * @var string Secret merchant key
     */
    protected $merchant_secret;

    /**
     * @var string Payment version, currently always '0001' (AN 4)
     */
    protected $version;
    /**
     * @var string Unique identifier for the payment (AN 20)
     */
    protected $stamp;
    /**
     * @var string Amount of payment in cents (10€ == 1000) (N 8)
     */
    protected $amount;
    /**
     * @var string Reference number for the payment, recommended to be unique but not forced (AN 20)
     */
    protected $reference;
    /**
     * @var string Message/Description for the buyer (Nuts and bolts, Furniture, Cuckoo clocks) (AN 1000)
     */
    protected $message;
    /**
     * @var string Language of the payment selection page/bank interface if supported. Currently supported languages include Finnish FI, Swedish SE and English EN (AN  2)
     */
    protected $language;

    /**
     * @var string Url called when returning successfully (AN 300)
     */
    protected $return_url;
    /**
     * @var string Url called when user cancelled payment (AN 300)
     */
    protected $cancel_url;
    /**
     * @var string Url called when payment was rejected (No credit on credit card etc) (AN 300)
     */
    protected $reject_url;
    /**
     * @var string Url called when payment is initially successful but not yet confirmed (AN 300)
     */
    protected $delayed_url;

    /**
     * @var string Country of the buyer, affects available payment methods (AN 3)
     */
    protected $country;
    /**
     * @var string Currency used in payment. Currently only EUR is supported (AN 3)
     */
    protected $currency;
    /**
     * @var string device or method used when creating new transaction. Affects how Checkout servers respond to posting the new payment 1 = HTML 10 = XML (N 2)
     */
    protected $device;
    /**
     * @var string Payment type or content of purchase. Used to differentiate between adult entertainment and everything else. 1 = normal, 2 = adult entertainment (N 2)
     */
    protected $content;
    /**
     * @var string Type, currently always 0 (N 1)
     */
    protected $type;
    /**
     * @var string Algorithm used when calculating mac, currently = 3. 1 and 2 are still available but deprecated (N 1)
     */
    protected $algorithm;

    /**
     * @var DateTime Expected delivery date (N 8) (Ymd)
     */
    protected $delivery_date;
    /**
     * @var string First name of customer (AN 40)
     */
    protected $first_name;
    /**
     * @var string Last name of customer (AN 40)
     */
    protected $family_name;
    /**
     * @var string Street address of customer (AN 40)
     */
    protected $address;
    /**
     * @var string Postcode of customer (AN 14)
     */
    protected $postcode;
    /**
     * @var string Post office of customer (AN 18)
     */
    protected $post_office;

    /**
     * @var bool If true overrides the minimum allowed amount check (by default 1€ is smallest allowed amount). Do not set to true unless you have a contract with Checkout Finland that allows smaller purchases then 1€.
     */
    private $allow_small_purchases;

    /**
     * @param $merchant_id
     * @param $merchant_secret
     * @param $allow_small_purchases
     */
    public function __construct($merchant_id, $merchant_secret, $allow_small_purchases = false)
    {
        $this->merchant_id      = $merchant_id;
        $this->merchant_secret  = $merchant_secret;

        $this->allow_small_purchases = $allow_small_purchases;

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

        $this->address = substr($address, 0, 40);
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
        $this->algorithm = substr($algorithm, 0 , 1);
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
     * @throws AmountTooLargeException
     * @throws AmountUnderMinimumException
     */
    public function setAmount($amount)
    {
        if(strlen($amount) > 8 )
            throw new AmountTooLargeException($amount ." is too large.");

        if($this->allow_small_purchases == false and $amount < 100)
            throw new AmountUnderMinimumException("1€ is the minimum allowed amount.");

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
     * @throws UrlTooLongException
     */
    public function setCancelUrl($cancel_url)
    {

        if(strlen($cancel_url) > 300)
            throw new UrlTooLongException('Max url length is 300 characters');

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
     * @throws UrlTooLongException
     */
    public function setDelayedUrl($delayed_url)
    {
        if(strlen($delayed_url) > 300)
            throw new UrlTooLongException('Max url length is 300 characters');

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
     * @throws UrlTooLongException
     */
    public function setRejectUrl($reject_url)
    {
        if(strlen($reject_url) > 300)
            throw new UrlTooLongException('Max url length is 300 characters');

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
     * @throws UrlTooLongException
     */
    public function setReturnUrl($return_url)
    {
        if(strlen($return_url) > 300)
            throw new UrlTooLongException('Max url length is 300 characters');

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
