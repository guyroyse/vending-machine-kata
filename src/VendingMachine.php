<?php
namespace My;

class VendingMachine
{
    public $coins = array();
    public $coinReturn = array();
    public $products = array();

    public function accept($coin)
    {
        $cv = $coin->value();
        if ($cv == -1)
            $this->coinReturn[] = $coin;
        else
            $this->coins[] = $coin;
    }

    public function display()
    {
        $tot = $this->currentAmount();
        return $tot == 0 ? "INSERT COIN" : sprintf("$%0.2f", $tot / 100);
    }

    private function currentAmount()
    {
        $tot = 0;
        foreach ($this->coins as $coin) {
            $tot += $coin->value();
        }
        return $tot;
    }

    public function load(Product $p)
    {
        foreach ($this->products as $product) {
            if ($product->name == $p->name) {
                $this->product->auantity += $p->quantity;
                return;
            }
        }
        $this->products[$p->name] = $p;
    }

    public function select($item)
    {
        $keys = array_keys($this->products);
        $product = null;

        // find the product
        foreach($keys as $index => $name) {
            if ($item == $name) {
                $product = $this->products[$name];
                break;
            }
        }
        if (is_null($product))
            return "NO SUCH ITEM";

        if ($product->quantity <= 0)
            return "OUT OF ITEM";

        if ($this->currentAmount() < $product->price)
            return "PRICE ".sprintf("$%0.2f", $product->price / 100);

        // decrement quantity
        $this->products[$item]->quantity--;
        return "THANK YOU";
    }
}
