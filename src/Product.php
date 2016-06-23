<?php
namespace My;

/**
 * class Product
 *
 * A Product has a name, quantity, and price.
 * A "null" Product has a name that is null.
 */
class Product
{
    public $name;
    public $price;
    public $quantity;

    /**
     * construct one product
     *
     * @param array (name, price, quantity)
     *
     * @return void
     */
    public function __construct($name = null, $price = 0, $quantity = 0)
    {
        $this->name = $name;
        $this->price = $price;
        $this->quantity = $quantity;
    }

    /**
     * isNull() returns true if this is a null Product
     *
     * @return bool
     */
    public function isNull()
    {
        return $this->name === null;
    }
}
