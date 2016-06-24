<?php
namespace My;

/**
 * Created by PhpStorm.
 * User: sheiss
 * Date: 6/20/16
 * Time: 6:00 PM
 */

class SelectThankYou extends SelectStrategy
{
    /**
     * successful item selection
     * item is available for purchase
     * sufficient purchasing funds available
     * change can be made if necessary
     *
     * @param $vm
     * @param $product
     * @param $changeResults
     * @return string
     */
    public function select(VendingMachine $vm, Product $product, array $changeResults)
    {
        // update products
        $vm->updateProducts($product->name);

        // move the coins to where they belong
        $vm->updateCoinContainers($changeResults);
        return "THANK YOU";
    }
}
