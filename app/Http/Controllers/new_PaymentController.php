<?php

namespace App\Http\Controllers;

use Anand\LaravelPaytmWallet\Facades\PaytmWallet;
use App\Models\Pricing;
use App\Models\payment_gateway\Paystack;
use App\Models\payment_gateway\Ccavenue;
use App\Models\payment_gateway\Pagseguro;
use App\Models\payment_gateway\Xendit;
use App\Models\payment_gateway\Doku;
use App\Models\payment_gateway\Skrill;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class PaymentController extends Controller
{
    protected $pricing; protected $paystack; protected $ccavenue; protected $pagseguro; protected $xendit; protected $doku; protected $skrill;

    public function __construct( Pricing $pricing, Paystack $paystack, Ccavenue $ccavenue, Pagseguro $pagseguro, Xendit $xendit, Doku $doku, Skrill $skrill) {
        $this->pricing   = $pricing; $this->paystack  = $paystack; $this->ccavenue  = $ccavenue; $this->pagseguro = $pagseguro; $this->xendit    = $xendit; $this->doku      = $doku; $this->skrill    = $skrill;
    }

    /**
     * Show payment page for a package
     */
    public function index($id)
    {
        try {
            $package = $this->pricing->findOrFail($id);

            $items[] = [ 'id'             => $package->id, 'title'          => sanitize($package->name), 'subtitle'       => sanitize($package->sub_title), 'price'          => sanitize($package->price), 'period'         => $package->period, 'discount_price' => 0,
            ];

            $payment_details = [
                'items'          => $items,
                'custom_field'   => [
                    'item_type'  => 'Package',
                    'pay_for'    => 'Package payment',
                    'user_id'    => auth()->id(),
                    'user_photo' => auth()->user()->photo,
                ],
                'success_method' => [
                    'model_name'    => 'PurchasePackage',
                    'function_name' => 'purchase_package',
                ],
                'tax'            => '',
                'coupon'         => '',
                'payable_amount' => round($package->price, 2),
                'cancel_url'     => route('pricing'),
                'success_url'    => route('payment.success', ''),
            ];

            Session::put('payment_details', $payment_details);

            $page_data['payment_details']  = $payment_details;
            $page_data['package']          = $package;
            $page_data['payment_gateways'] = $this->paystack->where('status', 1)->get();

            return view('payment.index', $page_data);
        } catch (\Exception $e) {
            Session::flash('danger', $e->getMessage());
            return redirect()->back();
        }
    }

    /**
     * Show payment gateway by identifier
     */
    public function show_payment_gateway_by_ajax($identifier)
    {
        $page_data['payment_details'] = session('payment_details');

        $gatewayModel = $this->getGatewayModel($identifier);
        $page_data['payment_gateway'] = $gatewayModel->where('identifier', $identifier)->first();

        return view("payment.$identifier.index", $page_data);
    }

    /**
     * Handle payment success
     */
    public function payment_success($identifier, Request $request)
    {
        $payment_details = session('payment_details');

        $gatewayModel = $this->getGatewayModel($identifier);
        $status = $gatewayModel->payment_status($identifier, $request->all());

        if ($status === true) {
            $success_model    = $payment_details['success_method']['model_name'];
            $success_function = $payment_details['success_method']['function_name'];

            $model_full_path = "App\\Models\\$success_model";
            return $model_full_path::$success_function($identifier);
        }

        Session::flash('error', get_phrase('Payment failed! Please try again.'));
        return redirect()->to($payment_details['cancel_url']);
    }

    /**
     * Create payment
     */
    public function payment_create($identifier)
    {
        $gatewayModel = $this->getGatewayModel($identifier);
        $created_payment_link = $gatewayModel->payment_create($identifier);

        return redirect()->to($created_payment_link);
    }

    /**
     * Razorpay specific checkout
     */
    public function payment_razorpay($identifier)
    {
        $gatewayModel = $this->getGatewayModel($identifier);
        $data = $gatewayModel->payment_create($identifier);

        return view('payment.razorpay.payment', compact('data'));
    }

    /**
     * Paytm order
     */
    public function make_paytm_order(Request $request)
    {
        return view('payment.paytm.paytm_merchant_checkout');
    }

    /**
     * Paytm callback
     */
    public function paytm_paymentCallback()
    {
        $transaction = PaytmWallet::with('receive');
        $order_id    = $transaction->getOrderId();

        if ($transaction->isSuccessful()) {
            Paytm::where('order_id', $order_id)->update([
                'status' => 1,
                'transaction_id' => $transaction->getTransactionId()
            ]);
            return redirect(route('initiate.payment'))->with('message', "Your payment is successful.");
        } elseif ($transaction->isFailed()) {
            Paytm::where('order_id', $order_id)->update([
                'status' => 0,
                'transaction_id' => $transaction->getTransactionId()
            ]);
            return redirect(route('initiate.payment'))->with('message', "Your payment failed.");
        } elseif ($transaction->isOpen()) {
            Paytm::where('order_id', $order_id)->update([
                'status' => 2,
                'transaction_id' => $transaction->getTransactionId()
            ]);
            return redirect(route('initiate.payment'))->with('message', "Your payment is processing.");
        }
    }

    /**
     * Helper: get injected gateway model
     */
    private function getGatewayModel($identifier)
    {
        switch ($identifier) {
            case 'paystack': return $this->paystack;
            case 'ccavenue': return $this->ccavenue;
            case 'pagseguro': return $this->pagseguro;
            case 'xendit': return $this->xendit;
            case 'doku': return $this->doku;
            case 'skrill': return $this->skrill;
            default: abort(404, 'Gateway not found');
        }
    }
}
