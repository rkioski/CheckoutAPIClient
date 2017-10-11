<?php

namespace CheckoutFinland;

/**
 * Class Client
 * @package CheckoutFinland
 */
class Client
{

    /**
     * Builds an array that can be passed to Checkout Finland as POST parameters. Sends the data. Returns the response body
     * that contains payment options xml or error message.
     *
     * @param Payment $payment
     * @throws \Exception
     * @return string
     */
    public function sendPayment(Payment $payment)
    {
        $postData = [
            'VERSION'       => ''. $payment->getVersion(),
            'STAMP'         => ''. $payment->getStamp(),
            'AMOUNT'        => ''. $payment->getAmount(),
            'REFERENCE'     => ''. $payment->getReference(),
            'MESSAGE'       => ''. $payment->getMessage(),
            'LANGUAGE'      => ''. $payment->getLanguage(),
            'MERCHANT'      => ''. $payment->getMerchantId(),
            'RETURN'        => ''. $payment->getReturnUrl(),
            'CANCEL'        => ''. $payment->getCancelUrl(),
            'REJECT'        => ''. $payment->getRejectUrl(),
            'DELAYED'       => ''. $payment->getDelayedUrl(),
            'COUNTRY'       => ''. $payment->getCountry(),
            'CURRENCY'      => ''. $payment->getCurrency(),
            'DEVICE'        => ''. $payment->getDevice(),
            'CONTENT'       => ''. $payment->getContent(),
            'TYPE'          => ''. $payment->getType(),
            'ALGORITHM'     => ''. $payment->getAlgorithm(),
            'DELIVERY_DATE' => ''. $payment->getDeliveryDate('Ymd'),
            'FIRSTNAME'     => ''. $payment->getFirstName(),
            'FAMILYNAME'    => ''. $payment->getFamilyName(),
            'ADDRESS'       => ''. $payment->getAddress(),
            'POSTCODE'      => ''. $payment->getPostcode(),
            'POSTOFFICE'    => ''. $payment->getPostOffice(),
            'MAC'           => ''. $payment->calculateMac()
        ];

        return $this->postData("https://payment.checkout.fi", $postData);
    }

    /**
     * Poll payment, returns with xml containing payment info or error message
     *
     * @param Poll $poll
     * @return string
     */
    public function poll(Poll $poll)
    {
        $postData = [
            'VERSION'   => $poll->getVersion(),
            'STAMP'     => $poll->getStamp(),
            'REFERENCE' => $poll->getReference(),
            'MERCHANT'  => $poll->getMerchantId(),
            'AMOUNT'    => $poll->getAmount(),
            'CURRENCY'  => $poll->getCurrency(),
            'FORMAT'    => $poll->getFormat(),
            'ALGORITHM' => $poll->getAlgorithm(),
            'MAC'       => $poll->calculateMac()
        ];

        return $this->postData('https://rpcapi.checkout.fi/poll2', $postData);

    }



    /**
     * Posts data, tries to use stream context if allow_url_fopen is on in php.ini or CURL if not. If neither option is available throws exception.
     *
     * @param $url
     * @param $postData
     * @throws \Exception
     * @return string
     */
    private function postData($url, $postData)
    {
        if(ini_get('allow_url_fopen'))
        {
            $context = stream_context_create(array(
                'http' => array(
                    'method' => 'POST',
                    'header' => 'Content-Type: application/x-www-form-urlencoded',
                    'content' => http_build_query($postData)
                )
            ));
            
            return file_get_contents($url, false, $context);
        } 
        elseif(in_array('curl', get_loaded_extensions()) ) 
        {
            $options = array(
                CURLOPT_POST            => 1,
                CURLOPT_HEADER          => 0,
                CURLOPT_URL             => $url,
                CURLOPT_FRESH_CONNECT   => 1,
                CURLOPT_RETURNTRANSFER  => 1,
                CURLOPT_FORBID_REUSE    => 1,
                CURLOPT_TIMEOUT         => 4,
                CURLOPT_POSTFIELDS      => http_build_query($postData)
            );
        
            $ch = curl_init();
            curl_setopt_array($ch, $options);
            $result = curl_exec($ch);
            curl_close($ch);

            return $result;
        }
        else 
        {
            throw new \Exception("No valid method to post data. Set allow_url_fopen setting to On in php.ini file or install curl extension.");
        }
    }
}
