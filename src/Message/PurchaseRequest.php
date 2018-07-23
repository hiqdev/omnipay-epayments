<?php

namespace Omnipay\ePayments\Message;

/**
 * Class PurchaseRequest
 *
 * @author Dmytro Naumenko <d.naumenko.a@gmail.com>
 */
class PurchaseRequest extends AbstractRequest
{

    public function getDetails()
    {
        return $this->getParameter('details');
    }

    public function setDetails($value)
    {
        return $this->setParameter('details', $value);
    }

    public function getNickName()
    {
        return $this->getParameter('NickName');
    }

    public function setNickName($value)
    {
        return $this->setParameter('NickName', $value);
    }

    public function getData()
    {
        $this->validate('partnerId', 'secret', 'orderId', 'amount', 'currency', 'details');

        $data = [];
        $data['partnerid'] = $this->getPartnerId();
        $data['orderid'] = $this->getOrderId();
        $data['amount'] = $this->getAmount();
        $data['currency'] = $this->getCurrency();
        $data['sign'] = $this->createSignature();

        if (!empty($this->getNickName())) {
            $data['nickname'] = $this->getNickName();
        }

        $data['details'] = $this->getDetails();
        $data['successurl'] = $this->getReturnUrl();
        $data['declineurl'] = $this->getCancelUrl();

        return $data;
    }

    public function createSignature()
    {
        $this->validate('partnerId', 'secret', 'orderId', 'amount', 'currency');

        $parts = [
            $this->getPartnerId(),
            $this->getSecret(),
            $this->getOrderId(),
            $this->getAmount(),
            $this->getCurrency(),
        ];

        return md5(implode(';', $parts));
    }

    public function sendData($data)
    {
        return $this->response = new PurchaseResponse($this, $data);
    }
}
