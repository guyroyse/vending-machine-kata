<?php
namespace My;

class Product
{
    public $name;
    public $quantity;
    public $price;

    public function __construct($name, $price, $quantity=0)
    {
        $this->name = $name;
        $this->price = $price;
        $this->quantity = $quantity;
    }
}
