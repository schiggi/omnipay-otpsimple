<?php

namespace Omnipay\OTPSimple\Message;

//use Omnipay\Common\Message\AbstractRequest;

/**
 * OTP Simple Purchase Request
 */
class PurchaseRequest extends AbstractRequest
{
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



        $hashData['MERCHANT'] = $data['MERCHANT'];
        $hashData['ORDER_REF'] = $data['ORDER_REF'];
        $hashData['ORDER_DATE'] = $data['ORDER_DATE'];

        $items = $this->getItems();
        if (!empty($items)) {
            foreach ($items as $key => $item) {
                $data['ORDER_PNAME[]'] = $item->getName();
                $hashData['ORDER_PNAME[]'] = $data['ORDER_PNAME[]'];
            }
            foreach ($items as $key => $item) {
                $data['ORDER_PCODE[]'] = 'n/a';
                $hashData['ORDER_PCODE[]'] = $data['ORDER_PCODE[]'];
            }
            foreach ($items as $key => $item) {
                $data['ORDER_PINFO[]'] = $item->getDescription();
                $hashData['ORDER_PINFO[]'] = $data['ORDER_PINFO[]'];
            }
            foreach ($items as $key => $item) {
                $data['ORDER_PRICE[]'] = $this->getAmount();
                $hashData['ORDER_PRICE[]'] = $data['ORDER_PRICE[]'];
            }
            foreach ($items as $key => $item) {
                $data['ORDER_QTY[]'] = $item->getQuantity();
                $hashData['ORDER_QTY[]'] = $data['ORDER_QTY[]'];
            }
            foreach ($items as $key => $item) {
                $data['ORDER_VAT[]'] = 0;
                $hashData['ORDER_VAT[]'] = $data['ORDER_VAT[]'];
            }
        } else {
            $data['ORDER_PNAME[]']      = $this->getDescription();
            $hashData['ORDER_PNAME[]']  = $data['ORDER_PNAME[]'];
            $data['ORDER_PCODE[]']      = 'n/a';
            $hashData['ORDER_PCODE[]']  = $data['ORDER_PCODE[]'];
            $data['ORDER_PRICE[]']      = $this->getAmount();
            $hashData['ORDER_PRICE[]']  = $data['ORDER_PRICE[]'];
            $data['ORDER_QTY[]']        = 1;
            $hashData['ORDER_QTY[]']    = $data['ORDER_QTY[]'];
            $data['ORDER_VAT[]']        = 0;
            $hashData['ORDER_VAT[]']    = $data['ORDER_VAT[]'];
        }

        $data['ORDER_SHIPPING'] = 0;
        $data['DISCOUNT'] = 0;

        $hashData['ORDER_SHIPPING']     = $data['ORDER_SHIPPING'];
        $hashData['PRICES_CURRENCY']    = $data['PRICES_CURRENCY'];
        $hashData['DISCOUNT']           = $data['DISCOUNT'];
        $hashData['PAY_METHOD']         = $data['PAY_METHOD'];

        $data["ORDER_HASH"]             = $this->generateSignature($hashData, $this->getSecretKey());

        return $data;
    }

    public function sendData($data)
    {
        return $this->response = new PurchaseResponse($this, $data, $this->getEndpoint() );
    }
}