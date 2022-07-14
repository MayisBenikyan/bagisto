<?php

namespace TCO\Payment\Idram\Http\Controllers;

use TCO\Payment\Idram\Payment\Idram;
use Webkul\Checkout\Models\Cart;
use Webkul\Checkout\Repositories\CartRepository;
use Webkul\Sales\Repositories\OrderRepository;
use Webkul\Sales\Repositories\InvoiceRepository;

class IdramController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @param  OrderRepository  $orderRepository
     * @return void
     */
    public function __construct(
        protected CartRepository $cartRepository,
        protected OrderRepository $orderRepository,
        protected InvoiceRepository $invoiceRepository
    )
    {
    }

    /**
     * Redirects to the idram.
     */
    public function redirect()
    {
        return view('idram::form');
    }

    /**
     * Verify payment from idram.
     */
    public function verify(){
        $idram = new Idram();

        $idram_credentials = [
            'business_account' => $idram->getConfigData('business_account'),
            'secret_key' => $idram->getConfigData('secret_key')
        ];

        if (empty($_REQUEST['EDP_REC_ACCOUNT'])) {
            return abort(404);
        }

        if (isset($_REQUEST['EDP_PRECHECK']) && isset($_REQUEST['EDP_BILL_NO']) && isset($_REQUEST['EDP_REC_ACCOUNT']) && isset($_REQUEST['EDP_AMOUNT'])) {
            if ($_REQUEST['EDP_PRECHECK'] == "YES") {
                if ($_REQUEST['EDP_REC_ACCOUNT'] == $idram_credentials['business_account']) {
                    $cart = $this->cartRepository->find($_REQUEST['EDP_BILL_NO']);
                    if (!is_null($cart)) {
                        echo("OK");
                    }
                }
            }
        }

        if (isset($_REQUEST['EDP_PAYER_ACCOUNT']) && isset($_REQUEST['EDP_BILL_NO']) && isset($_REQUEST['EDP_REC_ACCOUNT'])
            && isset($_REQUEST['EDP_AMOUNT']) && isset($_REQUEST['EDP_TRANS_ID']) && isset($_REQUEST['EDP_CHECKSUM'])
            && !empty($idram_credentials['business_account']) && !empty($idram_credentials['secret_key'])) {

            $cart = $this->cartRepository->find($_REQUEST['EDP_BILL_NO']);

            $txtToHash =
                $idram_credentials['business_account'] . ":" .
                $cart->grand_total . ":" .
                $idram_credentials['secret_key'] . ":" .
                $_REQUEST['EDP_BILL_NO'] . ":" .
                $_REQUEST['EDP_PAYER_ACCOUNT'] . ":" .
                $_REQUEST['EDP_TRANS_ID'] . ":" .
                $_REQUEST['EDP_TRANS_DATE'];

            if (strtoupper($_REQUEST['EDP_CHECKSUM']) != strtoupper(md5($txtToHash))) {
                return abort(404);
            } else {
                echo("OK");
            }
        }
    }

    /**
     * Cancel payment from idram.
     */
    public function cancel()
    {
        session()->flash('error', 'Idram payment has been canceled.');

        return redirect()->route('shop.checkout.cart.index');
    }

    /**
     * Success payment.
     */
    public function success()
    {
        $order = $this->orderRepository->create(Cart::prepareDataForOrder());

        Cart::deActivateCart();

        session()->flash('order', $order);

        return redirect()->route('shop.checkout.success');
    }
}