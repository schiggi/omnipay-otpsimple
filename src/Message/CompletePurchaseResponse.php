<?php

namespace Omnipay\OTPSimple\Message;

use Omnipay\Common\Message\AbstractResponse;

/**
 * New Complete Purchase response
 */
class CompletePurchaseResponse extends AbstractResponse
{
    public function isSuccessful()
    {
        return isset($this->data['decision']) && 'PAYMENT_AUTHORIZED' === $this->data['ORDERSTATUS'];
    }

    public function getTransactionId()
    {
        return isset($this->data['REFNOEXT']) ? $this->data['REFNOEXT'] : null;
    }

    public function getTransactionReference()
    {
        return isset($this->data['REFNO']) ? $this->data['REFNO'] : null;
    }

    public function getMessage()
    {
        return isset($this->data['message']) ? $this->data['message'] : null;
    }
}