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

            $httpResponse = $this->httpClient->post(
                $this->getEndpoint(),
                [
                    'Accept' => 'application/json',
                    'Content-type' => 'application/x-www-form-urlencoded',
                ],
                $requestBody
            );

            $responseBody = (string) $httpResponse->getBody();
            $response = json_decode($responseBody, true) ?? [];

            return new AuthenticateResponse($this, $response);
        } catch (\Exception $e) {
            throw new InvalidResponseException(
                'Error communicating with payment gateway: ' . $e->getMessage(),
                $e->getCode()
            );
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
