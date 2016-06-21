<?php
/**
 * Created by PhpStorm.
 * User: sheiss
 * Date: 6/20/16
 * Time: 6:00 PM
 */

namespace My;

class SelectThankYou
{
    /**
     * successful item selection
     * item is available for purchase
     * sufficient purchasing funds available
     * change can be made if necessary
     *
     * @param $vm
     * @param $item
     * @param $coinsToKeepAndReturn
     * @return string
     */
    public function __invoke($vm, $item, $coinsToKeepAndReturn)
    {
        $coinsToKeep = $coinsToKeepAndReturn['received'];
        $coinsToReturn = $coinsToKeepAndReturn['change'];

        // update products
        $vm->updateProducts($item);

        // move the coins to where they belong
        $vm->updateCoinContainers($coinsToKeep, $coinsToReturn);
        return "THANK YOU";
    }
}