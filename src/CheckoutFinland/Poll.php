<?php

namespace CheckoutFinland;

class Poll
{

	/**
     * @var string Payment version, currently always '0001' (AN 4)
     */
    protected $version;
    /**
     * @var string Unique identifier for the payment (AN 20)
     */
    protected $stamp;
    /**
     * @var string Reference number for the payment, recommended to be unique but not forced (AN 20)
     */
    protected $reference;
    /**
     * @var string Merchant id (AN 20)
     */
    protected $merchantId;
    /**
     * @var string Secret merchant key
     */
    protected $merchantSecret;
    /**
     * @var string Amount of payment in cents (10€ == 1000) (N 8)
     */
    protected $amount;
    /**
     * @var string Currency used in payment. Currently only EUR is supported (AN 3)
     */
    protected $currency;
    /**
     * @var string format Currently only 1
     */
    protected $format;
    /**
     * @var string Algorithm used when calculating mac, currently = 3. 1 and 2 are still available but deprecated (N 1)
     */
    protected $algorithm;

	/**
	 *	@param $merchantId
	 *	@param $merchantSecret
	 *
	 */
    public function __construct($merchantId, $merchantSecret)
    {
    	$this->merchantId = $merchantId;
    	$this->merchantSecret = $merchantSecret;

    	$this->setDefaultValues();
    }

	/**
     * @return $this
     */
    private function setDefaultValues()
    {
        $this->version      = '0001';
        $this->algorithm    = '3';
        $this->currency     = 'EUR';
        $this->format 		= '1';

        return $this;
    }

    /**
 	 * Set the Payment info
     *
     * @param $stamp
     * @param $reference
     * @param $amount
     * @return $this
     */
    public function setPaymentData($stamp, $reference, $amount)
    {
        $this->stamp = $stamp;
        $this->reference = $reference;
        $this->amount = $amount;

        return $this;
    }

    /**
     *   Calculates MAC string from all variables in the class
     *
     *   @return string
     */
    public function calculateMac()
    {
        $mac_string = $this->getVersion();

        $mac_string .= '+' .$this->getStamp();
        $mac_string .= '+' .$this->getReference();
        $mac_string .= '+' .$this->getMerchantId();
        $mac_string .= '+' .$this->getAmount();
        $mac_string .= '+' .$this->getCurrency();
        $mac_string .= '+' .$this->getFormat();
        $mac_string .= '+' .$this->getAlgorithm();

        return hash('sha256', $mac_string ."+" . $this->getMerchantSecret());
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
    public function getMerchantId()
    {
        return $this->merchantId;
    }

    /**
     * @param string $merchantId
     */
    public function setMerchantId($merchantId)
    {
        $this->merchantId = $merchantId;
    }

    /**
     * @return string
     */
    public function getMerchantSecret()
    {
        return $this->merchantSecret;
    }

    /**
     * @param string $merchantSecret
     */
    public function setMerchantSecret($merchantSecret)
    {
        $this->merchantSecret = $merchantSecret;
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
    public function getFormat()
    {
        return $this->format;
    }

    /**
     * @param string $format
     */
    public function setFormat($format)
    {
        $this->format = $format;
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




}