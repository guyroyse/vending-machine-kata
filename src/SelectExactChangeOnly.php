<?php
/**
 * Created by PhpStorm.
 * User: sheiss
 * Date: 6/20/16
 * Time: 6:52 PM
 */

namespace My;


class SelectExactChangeOnly
{
    /**
     * Attempt to select product when change cannot be made
     *
     * @param VendingMachine $vm
     * @return string
     */
    public function __invoke($vm)
    {
        $vm->returnCoins();
        return "EXACT CHANGE ONLY";
    }

}