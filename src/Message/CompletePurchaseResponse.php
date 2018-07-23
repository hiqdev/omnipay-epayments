<?php

namespace Omnipay\ePayments\Message;

use Omnipay\Common\Exception\InvalidResponseException;
use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Common\Message\RequestInterface;

class CompletePurchaseResponse extends AbstractResponse
{
    /**
     * @return RequestInterface|AbstractRequest
     */
    public function getRequest()
    {
        return parent::getRequest();
    }

    /**
     * CompletePurchaseResponse constructor.
     * @param RequestInterface $request
     * @param mixed $data
     * @throws \Exception
     */
    public function __construct(RequestInterface $request, $data)
    {
        parent::__construct($request, $data);

        $this->ensureSignatureIsCorrect();
    }

    /**
     * @throws InvalidResponseException
     */
    private function ensureSignatureIsCorrect(): void
    {
        if ($this->getCode()) {
            throw new InvalidResponseException(sprintf(
                'Payment for order "%s" failed with code "%s"',
                $this->getOrderId(),
                $this->getCode()
            ));
        }

        if ($this->getSign() !== $this->createSignature()) {
            throw new InvalidResponseException(sprintf(
                'Failed to validate signature for order "%s"',
                $this->getOrderId()
            ));
        }
    }

    private function createSignature(): string
    {
        return md5(implode(';', [
            $this->getOrderId(),
            $this->getTransactionReference(),
            $this->getRequest()->getSecret()
        ]));
    }

    private function getSign(): ?string
    {
        return $this->data['sign'] ?? null;
    }

    public function getMessage()
    {
        return $this->data['msg'] ?? 'Something went wrong';
    }

    public function getCode()
    {
        return $this->data['code'] ?? null;
    }

    public function getOrderId()
    {
        return $this->data['orderId'] ?? null;
    }

    public function isSuccessful()
    {
        return $this->getTransactionReference() !== null;
    }

    public function getTransactionReference()
    {
        return $this->data['transactionId'] ?? null;
    }

    public function isRedirect()
    {
        return false;
    }
}
