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
    public function generateSignature($data, $secret_key, $skip_field = array())
    {
        //remove named key, if needed
//        if (!empty($skip_field) && array_key_exists($skip_field,$data)) {
//            unset( $data[$skip_field] );
//        }
        $data_flat = $this->flatArray($data,$skip_field);

        //begin HASH calculation
        $hashString = "";
        foreach ($data_flat as $key => $val) {
            $hashString .= strlen($val) . $val;
        }
        return hash_hmac("md5", $hashString, $secret_key);

    }

    /**
     * Creates a 1-dimension array from a 2-dimension one
     *
     * @param array $array Array to be processed
     * @param array $skip  Array of keys to be skipped when creating the new array
     *
     * @return array $return Flat array
     *
     */
    public function flatArray($array, $skip = array())
    {
        $return = array();
        foreach ($array as $name => $item) {
            if (!in_array($name, $skip)) {
                if (is_array($item)) {
                    foreach ($item as $subItem) {
                        $return[] = $subItem;
                    }
                } elseif (!is_array($item)) {
                    $return[] = $item;
                }
            }
        }
        return $return;
    }

    public function supportsDeleteCard()
    {
        return false;
    }
}