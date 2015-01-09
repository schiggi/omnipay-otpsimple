<?php
namespace Omnipay\PayU;

use Omnipay\Common\AbstractGateway;


class Gateway extends AbstractGateway
{
    public function getName()
    {
        return 'PayU';
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
            'MERCHANT' => '',
        );
    }

    /**
     * @param array $parameters
     * @return \Omnipay\PayU\Message\PurchaseRequest
     */
    public function purchase(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\PayU\Message\PurchaseRequest', $parameters);
    }
}