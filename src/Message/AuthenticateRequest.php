<?php

namespace Omnipay\ePayments\Message;

use Omnipay\Common\Exception\InvalidResponseException;
use Omnipay\Common\Message\ResponseInterface;

/**
 * Class AuthenticateRequest
 *
 * @author Dmytro Naumenko <d.naumenko.a@gmail.com>
 */
class AuthenticateRequest extends AbstractRequest
{
    public function getEndpoint(): string
    {
        return $this->getTestMode()
            ? 'https://api.sandbox.epayments.com/token'
            : 'https://api.epayments.com/token';
    }

    /**
     * Get the raw data array for this message. The format of this varies from gateway to
     * gateway, but will usually be either an associative array, or a SimpleXMLElement.
     *
     * @return mixed
     * @throws \Omnipay\Common\Exception\InvalidRequestException
     */
    public function getData()
    {
        $this->validate('partnerId', 'secret');

        return [
            'grant_type' => 'partner',
            'partner_id' => $this->getPartnerId(),
            'partner_secret' => $this->getSecret(),
            'expires_in' => 60
        ];
    }

    /**
     * Send the request with specified data
     *
     * @param  mixed $data The data to send
     * @return ResponseInterface|AuthenticateResponse
     * @throws InvalidResponseException
     */
    public function sendData($data): ResponseInterface
    {
        try {
            $requestBody = http_build_query($data);

            $httpResponse = $this->httpClient->request(
                'POST',
                $this->getEndpoint(),
                [
                    'Accept' => 'application/json',
                    'Content-type' => 'application/x-www-form-urlencoded',
                ],
                $requestBody
            );

            $responseBody = (string) $httpResponse->getBody()->getContents();
            $response = json_decode($responseBody, true, 512, JSON_THROW_ON_ERROR) ?? [];

            return new AuthenticateResponse($this, $response);
        } catch (\Exception $e) {
            throw new InvalidResponseException(sprintf(
                'Error communicating with payment gateway: %s',
                $e->getMessage()
            ), $e->getCode(), $e);
        }
    }

    /**
     * @return ResponseInterface|AuthenticateResponse
     */
    public function send()
    {
        return parent::send();
    }
}
