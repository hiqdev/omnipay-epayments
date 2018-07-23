<?php

namespace Omnipay\ePayments\Message;

use Omnipay\Common\Exception\InvalidResponseException;
use Omnipay\Common\Message\RequestInterface;

/**
 * Class AuthenticateResponse
 *
 * @author Dmytro Naumenko <d.naumenko.a@gmail.com>
 */
class AuthenticateResponse extends \Omnipay\Common\Message\AbstractResponse
{
    public function __construct(RequestInterface $request, $data)
    {
        parent::__construct($request, $data);

        if (!$this->isSuccessful()) {
            throw new InvalidResponseException(sprintf(
                'Failed to acquire an access token. Error: "%s"',
                $this->getMessage()
            ));
        }
    }

    public function getAccessToken(): string
    {
        return $this->data['access_token'];
    }

    public function getMessage(): string
    {
        if (empty($this->data)) {
            return 'Empty response message';
        }

        return $this->data['error'];
    }

    /**
     * Is the response successful?
     *
     * @return boolean
     */
    public function isSuccessful(): bool
    {
        return !isset($this->data['error']) && !empty($this->data);
    }
}
