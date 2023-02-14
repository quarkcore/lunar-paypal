<?php

namespace Lancodev\LunarPaypal;

use Lunar\Base\DataTransferObjects\PaymentAuthorize;
use Lunar\Base\DataTransferObjects\PaymentCapture;
use Lunar\Base\DataTransferObjects\PaymentRefund;
use Lunar\Models\Transaction;
use Lunar\PaymentTypes\AbstractPayment;
use Srmklive\PayPal\Services\PayPal as PayPalClient;

class PaypalPaymentType extends AbstractPayment
{
    protected string $accessToken;

    public string $clientId;

    protected $policy;

    public $payPalClient;

    public function __construct()
    {
        $mode = config('lunar-paypal.mode');
        $this->clientId = config("lunar-paypal.{$mode}.client_id");

        $this->payPalClient = new PayPalClient();
        $this->payPalClient->setApiCredentials(config('lunar-paypal'));
        $this->payPalClient->getAccessToken();
    }

    public function getClientToken()
    {
        return $this->payPalClient->getClientToken()['client_token'];
    }

    /**
     * Authorize the payment for processing.
     */
    public function authorize(): PaymentAuthorize
    {
        //
    }

    /**
     * Capture a payment for a transaction.
     *
     * @param  int  $amount
     */
    public function capture(Transaction $transaction, $amount = 0): PaymentCapture
    {
        //
    }

    /**
     * Refund a captured transaction
     *
     * @param  string|null  $notes
     */
    public function refund(Transaction $transaction, int $amount = 0, $notes = null): PaymentRefund
    {
        //
    }
}
