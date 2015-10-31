<?php

/*
 * ePayments plugin for PHP merchant library
 *
 * @link      https://github.com/hiqdev/php-merchant-epayments
 * @package   php-merchant-epayments
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2015, HiQDev (http://hiqdev.com/)
 */

namespace hiqdev\php\merchant\epayments;

class Merchant extends \hiqdev\php\merchant\Merchant
{
    protected static $_defaults = [
        'system'      => 'epayments',
        'label'       => 'ePayments',
        'actionUrl'   => 'https://api.sandbox.epayments.com/merchant/prepare',
        'confirmText' => 'OK',
        'lifetime'    => 300,
    ];

    public function getOrderId()
    {
        return $this->uniqId . $this->invoiceNo;
    }

    public function getSign()
    {
        return md5("{$this->purse};{$this->_secret};{$this->orderId};{$this->sum};{$this->currency}");
    }

    public function getInputs()
    {
        return [
            'Amount'     => $this->total,
            'Details'    => $this->description,
            'Currency'   => $this->currency,
            'PartnerId'  => $this->purse,
            'LifeTime'   => $this->lifetime,
            'SuccessUrl' => $this->successUrl,
            'DeclineUrl' => $this->failureUrl,
            'OrderId'    => $this->orderId,
            'Sign'       => $this->sign,
        ];
    }

    public function validateConfirmation($data)
    {
    }
}
