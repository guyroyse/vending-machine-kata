<?php
namespace My;

/**
 * Created by PhpStorm.
 * User: sheiss
 * Date: 6/20/16
 * Time: 6:30 PM
 */

abstract class SelectStrategy
{
    abstract public function select(VendingMachine $vm, Product $product, array $ary);
}
