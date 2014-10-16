<?php

namespace CheckoutFinland;

use CheckoutFinland\Exceptions\MacMismatchException;
use CheckoutFinland\Exceptions\UnsupportedAlgorithmException;

/**
 * Class Response
 * @package CheckoutFinland
 */
class Response
{

    /**
     * @var string Secret merchant key
     */
    protected $merchant_secret;

    /**
     * @var string Payment version, currently always '0001'
     */
    protected $version;
    /**
     * @var string Unique identifier for the payment, set by merchant when creating new payment
     */
    protected $stamp;
    /**
     * @var string Reference number for the payment, set by merchant when creating new payment, recommended to be unique but not forced
     */
    protected $reference;
    /**
     * @var string Archive id/Unique id for the payment, set by Checkout Finland
     */
    protected $payment;
    /**
     * @var integer
     */
    protected $status;
    /**
     * @var
     */
    protected $algorithm;
    /**
     * @var
     */
    protected $mac;

    /**
     * @param $merchant_secret
     */
    public function __construct($merchant_secret)
    {
        $this->merchant_secret  = $merchant_secret;
    }

    /**
     * @return bool
     * @throws MacMismatchException
     * @throws UnsupportedAlgorithmException
     */
    public function validate()
    {
        $expected_mac = null;

        if($this->algorithm == 3) {
            $expected_mac = strtoupper(hash_hmac("sha256","$this->version&$this->stamp&$this->reference&$this->payment&$this->status&$this->algorithm", $this->merchant_secret));
        }
        else {
            throw new UnsupportedAlgorithmException();
        }

        if($expected_mac === $this->mac) {
            return true;
        } else {
            throw new MacMismatchException();
        }
    }

    /**
     * @param $params
     */
    public function setRequestParams($params)
    {
        $params = array_change_key_case($params, CASE_UPPER);

        $this->version      = isset($params['VERSION']) ? $params['VERSION'] : null;
        $this->stamp        = isset($params['STAMP']) ? $params['STAMP'] : null;
        $this->reference    = isset($params['REFERENCE']) ? $params['REFERENCE'] : null;
        $this->payment      = isset($params['PAYMENT']) ? $params['PAYMENT'] : null;
        $this->status       = isset($params['STATUS']) ? $params['STATUS'] : null;
        $this->algorithm    = isset($params['ALGORITHM']) ? $params['ALGORITHM'] : null;
        $this->mac          = isset($params['MAC']) ? $params['MAC'] : null;
    }

    /**
     * @return mixed
     */
    public function getAlgorithm()
    {
        return $this->algorithm;
    }

    /**
     * @param mixed $algorithm
     */
    public function setAlgorithm($algorithm)
    {
        $this->algorithm = $algorithm;
    }

    /**
     * @return mixed
     */
    public function getMac()
    {
        return $this->mac;
    }

    /**
     * @param mixed $mac
     */
    public function setMac($mac)
    {
        $this->mac = $mac;
    }

    /**
     * @return mixed
     */
    public function getMerchantSecret()
    {
        return $this->merchant_secret;
    }

    /**
     * @param mixed $merchant_secret
     */
    public function setMerchantSecret($merchant_secret)
    {
        $this->merchant_secret = $merchant_secret;
    }

    /**
     * @return mixed
     */
    public function getPayment()
    {
        return $this->payment;
    }

    /**
     * @param mixed $payment
     */
    public function setPayment($payment)
    {
        $this->payment = $payment;
    }

    /**
     * @return mixed
     */
    public function getReference()
    {
        return $this->reference;
    }

    /**
     * @param mixed $reference
     */
    public function setReference($reference)
    {
        $this->reference = $reference;
    }

    /**
     * @return mixed
     */
    public function getStamp()
    {
        return $this->stamp;
    }

    /**
     * @param mixed $stamp
     */
    public function setStamp($stamp)
    {
        $this->stamp = $stamp;
    }

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param mixed $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * @return mixed
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * @param mixed $version
     */
    public function setVersion($version)
    {
        $this->version = $version;
    }
}
