<?php
namespace Omnipay\OTPSimple;

use Omnipay\Common\AbstractGateway;


class Gateway extends AbstractGateway
{
    public function getName()
    {
        return 'OTPSimple';
    }

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

    public function getDefaultParameters()
    {
        return array(
            'merchantId' => "", //merchant account ID (HUF)
            'secretKey' => "", //secret key for account ID (HUF)
            'METHOD' => "CCVISAMC",                                             //payment method     empty -> select payment method on PayU payment page OR [ CCVISAMC, WIRE ]
            'ORDER_DATE' => @date("Y-m-d H:i:s"),                                //date of transaction
            'LOGGER' => true,                                                   //transaction log
            'LOG_PATH' => 'log',                                                //path of log file
            'BACK_REF' => 'http://' . $_SERVER['HTTP_HOST'] . '/backref.php',        //url of payu payment backref page
            'TIMEOUT_URL' => 'http://' . $_SERVER['HTTP_HOST'] . '/timeout.php', //url of payu payment timeout page
            'IRN_BACK_URL' => 'http://' . $_SERVER['HTTP_HOST'] . '/irn.php',        //url of payu payment irn page
            'IDN_BACK_URL' => 'http://' . $_SERVER['HTTP_HOST'] . '/idn.php',        //url of payu payment idn page
            'CURL' => true,
            'ORDER_TIMEOUT' => 300,
            'LANGUAGE' => 'HU',
            'GET_DATA' => $_GET,
            'POST_DATA' => $_POST,
            'SERVER_DATA' => $_SERVER,
            'testMode' => true,
        );
    }

    /**
     * @param array $parameters
     * @return \Omnipay\OTPSimple\Message\PurchaseRequest
     */
    public function purchase(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\OTPSimple\Message\PurchaseRequest', $parameters);
    }
}