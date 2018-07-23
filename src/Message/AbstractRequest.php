<?php

namespace Omnipay\ePayments\Message;

/**
 * Class AbstractRequest
 *
 * @author Dmytro Naumenko <d.naumenko.a@gmail.com>
 */
abstract class AbstractRequest extends \Omnipay\Common\Message\AbstractRequest
{
    public function getPartnerId()
    {
        return $this->getParameter('partnerId');
    }

    public function setPartnerId($value)
    {
        return $this->setParameter('partnerId', $value);
    }

    public function getSecret()
    {
        return $this->getParameter('secret');
    }

    public function setSecret($value)
    {
        return $this->setParameter('secret', $value);
    }

    public function getOrderId()
    {
        return $this->getParameter('orderId');
    }

    public function setOrderId($value)
    {
        return $this->setParameter('orderId', $value);
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
}
