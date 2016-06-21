<?php
/**
 * Created by PhpStorm.
 * User: sheiss
 * Date: 6/20/16
 * Time: 6:30 PM
 */

namespace My;


class SelectEngine
{
    private $selector;
    private $coinsToKeepAndReturn;

    public function setSelector(Product $product, CoinCollection $coinCurrent, CoinCollection $coinBox)
    {
        if (is_null($product)) {
            $this->selector = SelectNoSuchItem::class;
        } elseif ($product->quantity <= 0) {
            $this->selector = SelectSoldOut::class;
        } elseif ($coinCurrent->value() < $product->price) {
            $this->selector = SelectInsufficientFunds::class;
        } elseif (is_null($this->coinsToKeepAndReturn = ChangeMaker::makeChange($product->price, $coinCurrent, $coinBox))) {
            $this->selector = SelectExactChangeOnly::class;
        } else {
            $this->selector = SelectThankYou::class;
        }
        \Codeception\Util\Debug::debug($this->selector);
        return $this->selector;
    }

    public function __invoke($vm=null, $product=null)
    {
        return $this->selector->__invoke($vm, $product);
    }

    public function getSelector()
    {
        return $this->selector;
    }
}