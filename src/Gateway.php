<?php

namespace Omnipay\ePayments;

use Omnipay\Common\AbstractGateway;
use Omnipay\Common\Message\AbstractRequest;
use Omnipay\ePayments\Message\AuthenticateRequest;
use Omnipay\ePayments\Message\CompletePurchaseRequest;
use Omnipay\ePayments\Message\DetailsRequest;
use Omnipay\ePayments\Message\DetailsResponse;
use Omnipay\ePayments\Message\PurchaseRequest;

/**
 * Class Gateway
 *
 * @author Dmytro Naumenko <d.naumenko.a@gmail.com>
 */
class Gateway extends AbstractGateway
{
    public function getName()
    {
        return 'ePayments';
    }

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

    /**
     * @param array $parameters
     * @return PurchaseRequest|\Omnipay\Common\Message\AbstractRequest
     */
    public function purchase(array $parameters = [])
    {
        return $this->createRequest(PurchaseRequest::class, $parameters);
    }

    /**
     * @param array $parameters
     * @return CompletePurchaseRequest|\Omnipay\Common\Message\AbstractRequest
     */
    public function completePurchase(array $parameters = [])
    {
        return $this->createRequest(CompletePurchaseRequest::class, $parameters);
    }

    /**
     * @param array $parameters
     * @return \Omnipay\Common\Message\AbstractRequest|DetailsResponse
     */
    public function details(array $parameters = [])
    {
        if (!isset($parameters['access_token'])) {
            $authentication = $this->authenticate($parameters)->send();
            $parameters['access_token'] = $authentication->getAccessToken();
        }

        return $this->createRequest(DetailsRequest::class, $parameters);
    }

    /**
     * @param array $parameters
     * @return AuthenticateRequest|AbstractRequest
     */
    public function authenticate(array $parameters = [])
    {
        return $this->createRequest(AuthenticateRequest::class, $parameters);
    }
}
