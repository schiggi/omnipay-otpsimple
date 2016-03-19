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
            'returnUrl' => '',        //url of payu payment backref page
            'timeoutUrl' => '', //url of payu payment timeout page
            'ORDER_TIMEOUT' => 300,
            'LANGUAGE' => 'HU',
            'GET_DATA' => $_GET,
            'POST_DATA' => $_POST,
            'SERVER_DATA' => $_SERVER,
            'testMode' => false,
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

    public function IPNResponse(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\OTPSimple\Message\completePurchaseRequest', $parameters);
    }
}