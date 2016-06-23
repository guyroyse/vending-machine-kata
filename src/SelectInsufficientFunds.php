<?php
namespace My;

/**
 * Created by PhpStorm.
 * User: sheiss
 * Date: 6/20/16
 * Time: 6:55 PM
 */

class SelectInsufficientFunds extends SelectStrategy
{
    /**
     * Attempt to select available product with insufficient funds
     *
     * @param $product
     * @return string
     */
    public function select(VendingMachine $vm, Product $product, array $ary)
    {
        return "PRICE " . sprintf("$%0.2f", $product->price / 100);
    }
}
