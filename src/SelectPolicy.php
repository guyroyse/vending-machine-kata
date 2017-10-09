<?php
namespace My;

/**
 * Class SelectPolicy
 *
 * Encapsulate the logic of choosing a strategy in this class.
 *
 * @package My
 */

class SelectPolicy
{
    private $changeResults;
    private $vm;
    private $product;

    /**
     * Constructor saves the $vm in instance variable
     * Also initialize $changeResults to empty array.
     *
     * @param VendingMachine $vm
     */
    public function __construct(VendingMachine $vm)
    {
        $this->vm = $vm;
        $this->changeResults = array();
    }

    /**
     * Choose a class to realize the strategy for the select() process.
     * Store the product and changeResults for the strategy class to use.
     *
     * @param $item
     * @return SelectExactChangeOnly|SelectInsufficientFunds|SelectNoSuchItem|SelectSoldOut|SelectThankYou
     */
    public function getStrategy($item)
    {
        // find the product
        $this->product = $this->vm->products->get($item);

        if ($this->product->isNull()) {
            return new SelectNoSuchItem;
        }
        if ($this->product->quantity <= 0) {
            return new SelectSoldOut;
        }
        if ($this->vm->coinCurrent->value() < $this->product->price) {
            return new SelectInsufficientFunds;
        }

        // try to make change
        $this->changeResults = ChangeMaker::makeChange($this->product->price, $this->vm->coinCurrent, $this->vm->coinBox);
        if (array() === $this->changeResults) {
            return new SelectExactChangeOnly;
        }
        return new SelectThankYou;
    }

    /**
     * Return the changeResults
     *
     * @return array
     */
    public function getChangeResults()
    {
        return $this->changeResults;
    }

    /**
     * Return the product that will be purchased
     *
     * @return mixed
     */
    public function getProduct()
    {
        return $this->product;
    }
}
