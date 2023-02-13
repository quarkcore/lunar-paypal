<?php

namespace Lancodev\LunarPaypal\Components;

use Livewire\Component;
use Lunar\Facades\CartSession;
use Lunar\Models\Cart;
use Lunar\Models\Country;

class PaymentForm extends Component
{
    public Cart $cart;

    public $returnUrl;

    public $policy;

    public $payPalScript;

    protected $listeners = [
        'createOrder',
        'onApprove',
        'cartUpdated' => 'refreshCart',
        'selectedShippingOption' => 'refreshCart',
    ];

    public function mount()
    {
        //
    }

    public function createOrder($data)
    {
        logger('creating order');
        logger($data);
    }

    public function onApprove($data)
    {
        logger('on approve');
        logger($data);

        $shippingName = explode(' ', $data['purchase_units'][0]['shipping']['name']['full_name']);
        $shippingAddress = $data['purchase_units'][0]['shipping']['address'];

        $this->cart->setShippingAddress([
            'first_name' => $shippingName[0],
            'last_name' => $shippingName[1],
            'line_one' => $shippingAddress['address_line_1'],
            'line_two' => $shippingAddress['address_line_2'],
            'city' => $shippingAddress['admin_area_2'],
            'state' => $shippingAddress['admin_area_1'],
            'postcode' => $shippingAddress['postal_code'],
            'country_id' => Country::where('iso2', $shippingAddress['country_code'])->first()->id,
        ]);

        $this->cart->createOrder();

        return redirect()->to($this->returnUrl);
    }

    public function submit()
    {
        logger('submitting');
    }

    public function refreshCart()
    {
        $this->cart = CartSession::current();
    }

    public function render()
    {
        return view('lunar-paypal::paypal.components.payment-form');
    }
}
