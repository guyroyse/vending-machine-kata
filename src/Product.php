<?php
namespace My;

/**
 * class Product
 *
 * a product has a name, quantity, and price
 */
class Product
{
    public $name;
    public $quantity;
    public $price;

    /**
     * construct one product
     *
     * @param array (name, price, quantity)
     *
     * @return void
     */
    public function __construct($name, $price, $quantity = 0)
    {
        $this->name = $name;
        $this->price = $price;
        $this->quantity = $quantity;
    }

    /**
     * Get a product from an array of products
     *
     * @param array of Product $products
     * @param string $item
     *
     * @return Product | void
     */
    public static function get($products, $item)
    {
        // find the product
        $pxx = array_filter($products, function ($ptmp) use ($item) {
            return $ptmp->name == $item ? $ptmp : null;
        });
        // return first elem of pxx or null if pxx is empty
        return array_shift($pxx);
    }
}
