<?php

namespace Omnipay\ePayments\Message;

use Omnipay\Common\Exception\InvalidResponseException;
use Omnipay\Common\Message\ResponseInterface;

/**
 * Class DetailsRequest
 *
 * @author Dmytro Naumenko <d.naumenko.a@gmail.com>
 */
class DetailsRequest extends AbstractRequest
{
    public function setAccessToken($value)
    {
        return $this->parameters->set('access_token', $value);
    }

    public function getAccessToken()
    {
        return $this->parameters->get('access_token');
    }

    public function getEndpoint(string $orderId): string
    {
        return 'https://api.epayments.com/v1/merchant/orders/' . $orderId;
    }

    public function getData()
    {
        $this->validate('orderId', 'access_token');

        return [];
    }


    /**
     * Send the request with specified data
     *
     * @param  mixed $data The data to send
     * @return ResponseInterface
     * @throws InvalidResponseException
     */
    public function sendData($data): ResponseInterface
    {
        try {
            $requestBody = http_build_query($data);

            $httpResponse = $this->httpClient->request(
                'GET',
                $this->getEndpoint($this->getOrderId()),
                [
                    'Accept' => 'application/json',
                    'Authorization' => 'Bearer ' . $this->getAccessToken(),
                ],
                $requestBody
            );

            $responseBody = (string) $httpResponse->getBody()->getContents();
            $response = json_decode($responseBody, true, 512, JSON_THROW_ON_ERROR) ?? [];

            return new DetailsResponse($this, $response);
        } catch (\Exception $e) {
            throw new InvalidResponseException(
                'Error communicating with payment gateway: ' . $e->getMessage(),
                $e->getCode(),
                $e
            );
        }
    }
}
