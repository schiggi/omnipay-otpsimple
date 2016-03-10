<?php

namespace Omnipay\OTPSimple\Message;

use Omnipay\Common\Message\AbstractRequest;

/**
 * PayU Purchase Request
 */
class PurchaseRequest extends AbstractRequest
{
    protected $liveEndpoint = 'https://secure.simplepay.hu/payment/order/lu.php';
    protected $testEndpoint = 'https://sandbox.simplepay.hu/payment/order/lu.php';

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

    public function getData()
    {
        $data = array();
        $data['MERCHANT'] = $this->getMerchantId();
        $data['ORDER_REF'] = $this->getTransactionId();
        $data['ORDER_DATE'] = gmdate('Y-m-d H:i:s');
        $data['PRICES_CURRENCY'] = $this->getCurrency();
        $data['PAY_METHOD'] = 'CCVISAMC';
        $card = $this->getCard();
        if ($card) {
            $data['BACK_REF'] = '';
            $data['CLIENT_IP'] = $this->getClientIp();
            $data['BILL_LNAME'] = $card->getBillingLastName();
            $data['BILL_FNAME'] = $card->getBillingFirstName();
            $data['BILL_EMAIL'] = $card->getEmail();
            $data['BILL_PHONE'] = $card->getBillingPhone();
            $data['BILL_COUNTRYCODE'] = $card->getBillingCountry();
            $data['DELIVERY_FNAME'] = $card->getShippingFirstName();
            $data['DELIVERY_LNAME'] = $card->getShippingLastName();
            $data['DELIVERY_PHONE'] = $card->getShippingPhone();
            $data['DELIVERY_ADDRESS'] = $card->getShippingAddress1();
            $data['DELIVERY_ZIPCODE'] = $card->getShippingPostcode();
            $data['DELIVERY_CITY'] = $card->getShippingCity();
            $data['DELIVERY_STATE'] = $card->getShippingState();
            $data['DELIVERY_COUNTRYCODE'] = $card->getShippingCountry();
        }
        $items = $this->getItems();
        if (!empty($items)) {
            foreach ($items as $key => $item) {
                $data['ORDER_PNAME[' . $key . ']'] = $item->getName();
                $data['ORDER_PCODE[' . $key . ']'] = $item->getName();
                $data['ORDER_PINFO[' . $key . ']'] = $item->getDescription();
                $data['ORDER_PRICE[' . $key . ']'] = $item->getPrice();
                $data['ORDER_QTY[' . $key . ']'] = $item->getQuantity();
            }

        }
        $data["ORDER_HASH"] = $this->generateHash($data);
        return $data;
    }

    public function sendData($data)
    {
        return $this->response = new PurchaseResponse($this, $data, $this->getEndpoint());
    }

    private function generateHash($data)
    {
        if ($this->getSecretKey()) {
            //begin HASH calculation
            ksort($data);
            $hashString = "";
            foreach ($data as $key => $val) {
                $hashString .= strlen($val) . $val;
            }
            return hash_hmac("md5", $hashString, $this->getSecretKey());
        }

    }
}