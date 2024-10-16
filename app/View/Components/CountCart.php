<?php

namespace App\View\Components;

use App\Cart\Cart;
use Illuminate\View\Component;
use Illuminate\View\View;

class CountCart extends Component
{
    public Cart $cart;

    public function __construct(Cart $cart)
    {
        $this->cart = $cart;
    }

    public function render():View
    {
        return view('components.count-cart', ['count' => $this->cart->getAmount()]);
    }
}
