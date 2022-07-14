<?php

namespace TCO\Payment\Arca\Http\Controllers;

use Illuminate\Support\Facades\App;
use TCO\Payment\Arca\Payment\Arca;
use Webkul\Checkout\Models\Cart;
use Webkul\Checkout\Repositories\CartRepository;
use Webkul\Sales\Repositories\OrderRepository;
use Webkul\Sales\Repositories\InvoiceRepository;

class ArcaController extends Controller
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
     * Redirects to the arca.
     */
    public function redirect()
    {
        $arca = new Arca();

        $arca_credentials = [
            'userName' => $arca->getConfigData('userName'),
            'password' => $arca->getConfigData('password')
        ];

        $cart = $arca->getCart();
        $lang = App::getLocale();
        $amount = explode('.',$cart->grand_total)[0].'.00';

        $arrayArca = [
            'amount' => $amount,
            'orderNumber' => $cart->id,
            'language' => $lang,
            'userName' => $arca_credentials['userName'],
            'password' => $arca_credentials['password'],
            'description' => 'Bagisto Arca payment',
            'returnUrl' => route('arca.verify'),
        ];

        $init = curl_init();

        curl_setopt($init, CURLOPT_URL, "https://ipay.arca.am/payment/rest/register.do");
        curl_setopt($init, CURLOPT_POST, 1);
        curl_setopt($init, CURLOPT_POSTFIELDS, $arrayArca);
        curl_setopt($init, CURLOPT_RETURNTRANSFER, true);

        $server_output = curl_exec($init);
        dd($server_output);
        curl_close($init);

        $status = json_decode($server_output);

        if ($status->errorCode == 0) {
            $formUrl = $status->formUrl;
            $orderId = $status->orderId;
            return view('arca::form', compact( 'formUrl', 'orderId'));
        }
    }

    /**
     * Verify payment from arca.
     */
    public function verify(){

    }

    /**
     * Cancel payment from arca.
     */
    public function cancel()
    {
        session()->flash('error', 'Arca payment has been canceled.');

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