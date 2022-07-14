<?php

namespace TCO\Payment\Idram\Payment;

use Webkul\Payment\Payment\Payment;

class Idram extends Payment
{
    /**
     * Payment method code
     *
     * @var string
     */
    protected $code  = 'idram';

    /**
     * Return idram redirect url.
     */
    public function getRedirectUrl()
    {
        return route('idram.redirect');
    }

    /**
     * Idram web URL generic getter
     *
     * @return string
     */
    public function getIdramUrl()
    {
        return 'https://money.idram.am/payment.aspx';
    }

    /**
     * Return form field array.
     *
     * @return array
     */
    public function getFormFields()
    {
        $cart = $this->getCart();

        $fields = [
            'EDP_LANGUAGE'    => 'AM',
            'EDP_BILL_NO'     => $cart->id,
            'EDP_DESCRIPTION' => 'Bagisto Idram payment',
            'EDP_AMOUNT'      => $cart->grand_total,
            'EDP_REC_ACCOUNT' => $this->getConfigData('business_account')
        ];

        return $fields;
    }
}