<?php

namespace Omnipay\OTPSimple\Message;

use Omnipay\Common\Message\AbstractRequest;
use Omnipay\Common\Exception\InvalidRequestException;

/**
 * OTP Simple Purchase Request
 */
class CompletePurchaseRequest extends AbstractRequest
{
    public function getHttpMethod()
    {
        return 'POST';
    }

    public function getData()
    {
        $data = $this->httpRequest->request->all();
        if ($this->generateSignature(
                $data,
                $this->getSecretKey(),
                'HASH'
            )
            != $data['HASH']) {
            throw new InvalidRequestException('signature mismatch');
        }
        return $data;
    }

    public function sendData($data)
    {
        return $this->response = new CompletePurchaseResponse($this, $data);
    }
}