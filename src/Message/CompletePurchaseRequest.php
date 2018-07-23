<?php

namespace Omnipay\ePayments\Message;

/**
 * Class CompletePurchaseRequest
 *
 * @author Dmytro Naumenko <d.naumenko.a@gmail.com>
 */
class CompletePurchaseRequest extends AbstractRequest
{
    public function getData()
    {
        $this->validate('secret');

        return $this->httpRequest->query->all();
    }

    public function sendData($data)
    {
        return $this->response = new CompletePurchaseResponse($this, $data);
    }
}
