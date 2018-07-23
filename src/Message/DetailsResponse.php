<?php

namespace Omnipay\ePayments\Message;

use Omnipay\Common\Exception\InvalidResponseException;
use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Common\Message\RequestInterface;

/**
 * Class DetailsResponse
 *
 * @author Dmytro Naumenko <d.naumenko.a@gmail.com>
 */
class DetailsResponse extends AbstractResponse
{
    private const STATE_PAID = 'Paid';

    /**
     * @return RequestInterface|DetailsRequest
     */
    public function getRequest()
    {
        return parent::getRequest();
    }

    public function __construct(RequestInterface $request, $data)
    {
        parent::__construct($request, $data);

        $this->ensureResponseIsValid($data);
        $this->data = reset($data['orders']);
    }

    private function ensureResponseIsValid(array $data): void
    {
        if (empty($data)) {
            throw new InvalidResponseException(sprintf(
                'Got empty response for order "%s" details request',
                $this->getRequest()->getOrderId()
            ));
        }

        if ((string)$data['errorCode'] !== '0') {
            throw new InvalidResponseException(sprintf(
                'Order "%s" details request failed with code "%s"',
                $this->getRequest()->getOrderId(),
                $data['errorCode']
            ));
        }

        if (\count($data['orders']) !== 1) {
            throw new InvalidResponseException(sprintf(
                'Expected to get exactly 1 order, got %s instead',
                \count($data['orders'])
            ));
        }
    }

    /**
     * Is the response successful?
     *
     * @return boolean
     */
    public function isSuccessful(): bool
    {
        return $this->data['state'] === self::STATE_PAID;
    }

    public function getAmount(): string
    {
        return $this->data['amount'];
    }

    public function getCurrency(): string
    {
        return strtoupper($this->data['currency']);
    }

    public function getPaymentDate(): \DateTime
    {
        $dateTime = new \DateTime($this->data['payDate']);
        $dateTime->setTimezone(new \DateTimeZone('UTC'));

        return $dateTime;
    }

    public function getTransactionReference(): string
    {
        return $this->data['paymentTransactionId'];
    }

    public function getTransactionId(): string
    {
        return $this->data['orderId'];
    }

    public function getState(): string
    {
        return $this->data['state'];
    }
}
