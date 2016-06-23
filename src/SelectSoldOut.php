<?php
namespace My;

/**
 * Created by PhpStorm.
 * User: sheiss
 * Date: 6/20/16
 * Time: 6:59 PM
 */

class SelectSoldOut extends SelectStrategy
{
    /**
     * Attempt to select sold-out product
     *
     * @return string
     */
    public function select(VendingMachine $vm, Product $product, array $ary)
    {
        return "SOLD OUT";
    }
}
