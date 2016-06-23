<?php
namespace My;

/**
 * Created by PhpStorm.
 * User: sheiss
 * Date: 6/20/16
 * Time: 6:52 PM
 */

class SelectExactChangeOnly extends SelectStrategy
{
    /**
     * Attempt to select product when change cannot be made
     *
     * @param VendingMachine $vm
     * @return string
     */
    public function select(VendingMachine $vm, Product $product, array $ary)
    {
        $vm->returnCoins();
        return "EXACT CHANGE ONLY";
    }
}
