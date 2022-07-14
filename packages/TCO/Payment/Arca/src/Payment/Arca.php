<?php

namespace TCO\Payment\Arca\Payment;

use Webkul\Payment\Payment\Payment;

class Arca extends Payment
{
    /**
     * Payment method code
     *
     * @var string
     */
    protected $code  = 'arca';

    /**
     * Return arca redirect url.
     */
    public function getRedirectUrl()
    {
        return route('arca.redirect');
    }
}