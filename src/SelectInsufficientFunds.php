<?php
/**
 * Created by PhpStorm.
 * User: sheiss
 * Date: 6/20/16
 * Time: 6:55 PM
 */

namespace My;


class SelectInsufficientFunds
{
    /**
     * Attempt to select available product with insufficient funds
     *
     * @param $product
     * @return string
     */
    public function __invoke($vm, $product)
    {
        return "PRICE " . sprintf("$%0.2f", $product->price / 100);
    }
}