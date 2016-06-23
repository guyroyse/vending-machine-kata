<?php
namespace My;

/**
 * class ProductCollection
 *
 * A ProductCollection is a collection of Product objects.
 * all() returns the underlying array of products.
 * load($product) loads a quantity of a particular product.
 * If the product already exists, its quantity is updated.
 * get($item) returns the Product who's name is $item.
 *
 */
class ProductCollection
{
    /** @var array of Product $products */
    private $products;

    /**
     * construct an empty collection
     *
     * @return void
     */
    public function __construct()
    {
        $this->products = array();
    }

    /**
     * Return the collection
     *
     * @return array of Product
     */
    public function all()
    {
        return $this->products;
    }

    /**
     * Get a product from the collection
     * Return a "null" Product if no match on $item
     * Otherwise return Product who's name is $item
     *
     * @param string $item
     *
     * @return Product
     */
    public function get($item)
    {
        if (array_key_exists($item, $this->products)) {
            return $this->products[$item];
        }
        return new Product(null);
    }

    /**
     * load a given product
     *
     * @param Product $product A given product object
     * @return void
     */
    public function load(Product $product)
    {
        if ($this->get($product->name)->isNull()) {
            $this->products[$product->name] = $product;
        } else {
            $this->products[$product->name]->quantity += $product->quantity;
        }
    }
}
