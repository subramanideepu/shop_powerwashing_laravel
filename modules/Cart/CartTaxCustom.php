<?php

namespace Modules\Cart;

use Modules\Support\Money;

class CartTaxCustom
{
    public function __construct(
        protected $name,
        protected $amount
    ) {}

    public function name()
    {
        return $this->name;
    }

    public function amount()
    {
        return Money::inDefaultCurrency($this->amount);
    }
}