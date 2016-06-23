<?php
namespace My;

/**
 * Created by PhpStorm.
 * User: sheiss
 * Date: 6/20/16
 * Time: 6:56 PM
 */

class SelectNoSuchItem extends SelectStrategy
{
    /**
     * Attempt to select non-existant product
     *
     * @return string
     */
    public function select(VendingMachine $vm, Product $product, array $ary)
    {
        return "NO SUCH ITEM";
    }
}
