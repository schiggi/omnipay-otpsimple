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

    public function setTimeoutUrl($value)
    {
        return $this->setParameter('timeoutUrl', $value);
    }

    public function getTimeoutUrl()
    {
        return $this->getParameter('timeoutUrl');
    }

    public function getData()
    {
        $data = array();
        $hashData = array();

        $data['MERCHANT'] = $this->getMerchantId();
        $data['ORDER_REF'] = $this->getTransactionId();
        $data['ORDER_DATE'] = gmdate('Y-m-d H:i:s');
        $data['ORDER_TIMEOUT'] = 300;
        $data['PRICES_CURRENCY'] = $this->getCurrency();
        $data['PAY_METHOD'] = 'CCVISAMC';
        $data['LANGUAGE'] = 'HU';
        $data['AUTOMODE'] = 1;
        $card = $this->getCard();
        if ($card) {
            $data['BACK_REF'] = $this->getReturnUrl();
            $data['TIMEOUT_URL'] = $this->getTimeoutUrl();
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



        $hashData[] = $data['MERCHANT'];
        $hashData[] = $data['ORDER_REF'];
        $hashData[] = $data['ORDER_DATE'];

        $items = $this->getItems();
        if (!empty($items)) {
            foreach ($items as $key => $item) {
                $data['ORDER_PNAME[]'] = $item->getName();
                $hashData[] = $data['ORDER_PNAME[]'];
            }
            foreach ($items as $key => $item) {
                $data['ORDER_PCODE[]'] = 'n/a';
                $hashData[] = $data['ORDER_PCODE[]'];
            }
            foreach ($items as $key => $item) {
                $data['ORDER_PINFO[]'] = $item->getDescription();
                $hashData[] = $data['ORDER_PINFO[]'];
            }
            foreach ($items as $key => $item) {
                $data['ORDER_PRICE[]'] = $this->getAmount();
                $hashData[] = $data['ORDER_PRICE[]'];
            }
            foreach ($items as $key => $item) {
                $data['ORDER_QTY[]'] = $item->getQuantity();
                $hashData[] = $data['ORDER_QTY[]'];
            }
            foreach ($items as $key => $item) {
                $data['ORDER_VAT[]'] = 0;
                $hashData[] = $data['ORDER_VAT[]'];
            }
        } else {
            $data['ORDER_PNAME[]'] = $this->getDescription();
            $hashData[] = $data['ORDER_PNAME[]'];
            $data['ORDER_PCODE[]'] = 'n/a';
            $hashData[] = $data['ORDER_PCODE[]'];
//            $data['ORDER_PINFO[]'] = '';
//            $hashData[] = $data['ORDER_PINFO[]'];
            $data['ORDER_PRICE[]'] = $this->getAmount();
            $hashData[] = $data['ORDER_PRICE[]'];
            $data['ORDER_QTY[]'] = 1;
            $hashData[] = $data['ORDER_QTY[]'];
            $data['ORDER_VAT[]'] = 0;
            $hashData[] = $data['ORDER_VAT[]'];
        }

        $data['ORDER_SHIPPING'] = 0;
        $data['DISCOUNT'] = 0;

        $hashData[] = $data['ORDER_SHIPPING'];
        $hashData[] = $data['PRICES_CURRENCY'];
        $hashData[] = $data['DISCOUNT'];
        $hashData[] = $data['PAY_METHOD'];
        $data["ORDER_HASH"] = $this->generateHash($hashData);

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
            $hashString = "";
            foreach ($data as $key => $val) {
                $hashString .= strlen($val) . $val;
            }
            return hash_hmac("md5", $hashString, $this->getSecretKey());
        }

    }
}