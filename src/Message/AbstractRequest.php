<?php
namespace Omnipay\OTPSimple\Message;

//use Omnipay\Common\Message\RequestInterface;

/**
 *
 * @method \Omnipay\OTPSimple\Message\Response send()
 */
abstract class AbstractRequest extends \Omnipay\Common\Message\AbstractRequest
{
    protected $liveEndpoint = 'https://secure.simplepay.hu/payment/order/lu.php';
    protected $testEndpoint = 'https://sandbox.simplepay.hu/payment/order/lu.php';
    protected $endpoint = '';

//    public function sendData($data)
//    {
//        return $this->response = new Response($this, $data, $this->getEndpoint());
//    }

    public function getMerchantId()
    {
        return $this->getParameter('merchantId');
    }

    public function setMerchantId($value)
    {
        return $this->setParameter('merchantId', $value);
    }

    public function getSecretKey()
    {
        return $this->getParameter('secretKey');
    }

    public function setSecretKey($value)
    {
        return $this->setParameter('secretKey', $value);
    }

    public function getEndpoint()
    {
        return $this->getTestMode() ? $this->testEndpoint : $this->liveEndpoint;
    }

    public function getHttpMethod()
    {
        return 'POST';
    }


    /**
     *
     * @param array $data
     * @param array $fields
     * @param string $secret_key
     *
     * @return string
     */
    public function generateSignature($data, $secret_key, $skip_field = false)
    {
        //remove named key, if needed
        if (!empty($skip_field) && array_key_exists($skip_field,$data)) {
            unset( $data[$skip_field] );
        }

        //begin HASH calculation
        $hashString = "";
        foreach ($data as $key => $val) {
            $hashString .= strlen($val) . $val;
        }
        return hash_hmac("md5", $hashString, $secret_key);

    }

    public function supportsDeleteCard()
    {
        return false;
    }
}