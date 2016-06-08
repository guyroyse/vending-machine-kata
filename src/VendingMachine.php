<?php
namespace My;

// \Codeception\Util\Debug::debug("message");

class VendingMachine
{
    public $coinBox = array();
    public $coins = array();
    public $coinReturn = array();
    public $products = array();
    public $purchasedItem = null;

    /**
     * take the purchased item and change
     *
     * @return int array of item, change
     */
    public function takeItemAndChange()
    {
        $valueOfChange = $this->coinReturnAmount();
        $change = $this->coinReturn;
        \Codeception\Util\Debug::debug("# take change $valueOfChange");
        $this->coinReturn = array();
        $item = $this->purchasedItem;
        $this->purchasedItem = null;
        return array($item, $change);
    }

    /**
     * accept a coin or slug
     *
     * @return void
     */
    public function acceptCoin($coin)
    {
        if ($coin->value() <= 0) {
            $this->coinReturn[] = $coin;
        } else {
            $this->coins[] = $coin;
        }
    }

    /**
     * pre-load the coinbox
     *
     * @return void
     */
    public function loadCoinBox($coins)
    {
        $this->coinBox = $coins;
    }

    /**
     * display either "INSERT COIN" or the current amount of inserted coins
     *
     * @return string
     */
    public function display()
    {
        $tot = $this->currentAmount();
        return $tot == 0 ? "INSERT COIN" : sprintf("$%0.2f", $tot / 100);
    }

    /**
     * return value of coins inserted so far
     *
     * @return int
     */
    public function currentAmount()
    {
        return $this->calcCoinAmount($this->coins);
    }

    /**
     * return value of coins in coinbox
     *
     * @return int
     */
    public function coinBoxAmount()
    {
        return $this->calcCoinAmount($this->coinBox);
    }

    /**
     * return value of coins in coin return
     *
     * @return int
     */
    public function coinReturnAmount()
    {
        return $this->calcCoinAmount($this->coinReturn);
    }

    /**
     * return value of coins in an array of coins
     *
     * @return int
     */
    public function calcCoinAmount($coins)
    {
        $tot = 0;
        foreach ($coins as $coin) {
            $tot += $coin->value();
        }
        return $tot;
    }

    /**
     * load some quantity of a given product
     *
     * @param Product $product A given product object
     * @return void
     */
    public function loadProduct(Product $product)
    {
        if (array_key_exists($product->name, $this->products)) {
            $this->products[$product->name]->quantity += $product->quantity;
        } else {
            $this->products[$product->name] = $product;
        }
    }

    /**
     * select a product
     *
     * if invalid item, return NO SUCH ITEM
     * if quantity of selected item is zero, return SOLD OUT
     * if not enough coins inserted for selected item, return PRICE price_of_item
     * if cannot make change, return EXACT CHANGE ONLY
     * otherwise make the change, adjust item quantity, return THANK YOU
     *
     * @param $item A given product name
     * @return string
     */
    public function select($item)
    {
        $keys = array_keys($this->products);
        $product = null;

        // find the product
        foreach ($keys as $index => $name) {
            if ($item == $name) {
                $product = $this->products[$name];
                break;
            }
        }
        if (is_null($product)) {
            return "NO SUCH ITEM";
        }

        if ($product->quantity <= 0) {
            return "SOLD OUT";
        }

        if ($this->currentAmount() < $product->price) {
            return "PRICE ".sprintf("$%0.2f", $product->price / 100);
        }

        // make change
        if ($this->makeChange($product->price)) {
            // decrement item quantity
            $this->products[$item]->quantity--;
            // put purchased item in the tray
            $this->purchasedItem = $item;
            return "THANK YOU";
        } else {
            return "EXACT CHANGE ONLY";
        }
    }

    /**
     * cancel the current transaction
     *
     * @return void
     */
    public function cancel()
    {
        foreach ($this->coins as $coin) {
            $this->coinReturn[] = $coin;
        }
        $this->coins = array();
    }

    /**
     * make change if possible
     *
     * if unable to make change, cancel the current transaction
     *
     * @return boolean
     */
    private function makeChange($price)
    {
        $allCoins=array_merge($this->coins, $this->coinBox);
        sort($allCoins);
        $allCoins = array_reverse($allCoins);
        $coinsToReturn = array();
        $coinsToKeep = array();

        $valueOfChangeAvail = 0;
        $valueOfChangeNeeded = $this->currentAmount() - $price;
        foreach ($allCoins as $coin) {
            if ($valueOfChangeAvail + $coin->value() > $valueOfChangeNeeded) {
                $coinsToKeep[] = $coin;
            } else {
                $valueOfChangeAvail += $coin->value();
                $coinsToReturn[] = $coin;
            }
        }
        if ($valueOfChangeAvail == $valueOfChangeNeeded) {
            \Codeception\Util\Debug::debug("# made change $valueOfChangeNeeded");
            $this->coinBox = $coinsToKeep;
            foreach ($coinsToReturn as $coin) {
                $this->coinReturn[] = $coin;
            }
            $this->coins = array();
            return true;
        } else {
            \Codeception\Util\Debug::debug("# failed to make change $valueOfChangeNeeded");
            $this->cancel();
            return false;
        }
    }
}
