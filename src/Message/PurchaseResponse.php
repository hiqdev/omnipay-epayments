<?php

namespace Omnipay\ePayments\Message;

use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Common\Message\RedirectResponseInterface;

/**
 * Class PurchaseResponse
 *
 * @author Dmytro Naumenko <d.naumenko.a@gmail.com>
 */
class PurchaseResponse extends AbstractResponse implements RedirectResponseInterface
{
    public function getRedirectUrl()
    {
        /** @var PurchaseRequest $request */
        $request = $this->getRequest();

        if ($request->getTestMode()) {
            return 'https://api.sandbox.epayments.com/merchant/prepare';
        }

        return 'https://api.epayments.com/merchant/prepare';
    }

    public function isSuccessful()
    {
        return false;
    }

    public function isRedirect()
    {
        return true;
    }

    public function getRedirectMethod()
    {
        return 'POST';
    }

    public function getRedirectData()
    {
        return $this->data;
    }
}
