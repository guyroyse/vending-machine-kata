<?php
namespace My;

// \Codeception\Util\Debug::debug("message");

/**
 * class VendingMachine models a coin operated vending machine.
 *
 * A vending machine does the following:
 * Accept Coins
 * Select Product
 * Make Change
 * Return Coins
 * Sold Out
 * Exact Change Only
 */
class VendingMachine
{
    private $coinBox = array(); // array of Coin in the coin box
    public $coinCurrent = array(); // array of Coin inserted by current customer
    public $coinReturn = array(); // array of Coin to be returned to current customer
    public $products = array(); // array of Product in machine
    public $purchasedItem = null; // string

    /**
     * take the purchased item and change
     *
     * @return int array(item, change)
     */
    public function takeItemAndChange()
    {
        $change = $this->coinReturn;
        $this->coinReturn = array();
        $item = $this->purchasedItem;
        $this->purchasedItem = null;
        return array('item' => $item, 'change' => $change);
    }

    /**
     * accept a coin or slug
     *
     * @return void
     */
    public function acceptCoin(Coin $coin)
    {
        if ($coin->value() <= 0) { // slug
            $this->coinReturn[] = $coin;
            return;
        }
        $this->coinCurrent[] = $coin;
    }

    /**
     * pre-load the coinbox
     *
     * @return void
     */
    public function loadCoinBox(array $coins)
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
     * @SuppressWarnings(PHPMD.StaticAccess)
     * @return int
     */
    public function currentAmount()
    {
        return CoinArrayValue::valueOfCoins($this->coinCurrent);
    }

    /**
     * return value of coins in coinbox
     * used only in tests
     *
     * @SuppressWarnings(PHPMD.StaticAccess)
     * @return int
     */
    public function coinBoxAmount()
    {
        return CoinArrayValue::valueOfCoins($this->coinBox);
    }

    /**
     * return value of coins in coin return
     * used only in tests
     *
     * @SuppressWarnings(PHPMD.StaticAccess)
     * @return int
     */
    public function coinReturnAmount()
    {
        return CoinArrayValue::valueOfCoins($this->coinReturn);
    }

    /**
     * load a given product
     *
     * @param Product $product A given product object
     * @return void
     */
    public function loadProduct(Product $product)
    {
        if (array_key_exists($product->name, $this->products)) {
            $this->products[$product->name]->quantity += $product->quantity;
            return;
        }
        $this->products[$product->name] = $product;
    }

    /**
     * select a product
     *
     * if invalid item, return NO SUCH ITEM
     * if quantity of selected item is zero, return SOLD OUT
     * if not enough coins inserted for selected item, return PRICE price_of_item
     * if cannot make change, move inserted coins to coinReturn and return EXACT CHANGE ONLY
     * otherwise make the change, adjust item quantity and coins, put the purchased item in the try, return THANK YOU
     *
     * @param $item A given product name
     * @return string
     */
    public function select($item)
    {
        $product = null;

        // find the product
        $pxx = array_filter($this->products, function ($ptmp) use ($item) {
            return $ptmp->name == $item ? $ptmp : null;
        });
        // set product to first elem of pxx or null if pxx is empty
        $product = array_shift($pxx);

        if (is_null($product)) {
            return "NO SUCH ITEM";
        }

        if ($product->quantity <= 0) {
            return "SOLD OUT";
        }

        if ($this->currentAmount() < $product->price) {
            return "PRICE ".sprintf("$%0.2f", $product->price / 100);
        }

        // attempt to make change
        if (is_null($coinsToKeepAndReturn = $this->makeChange($product->price))) {
            $this->returnCoins();
            return "EXACT CHANGE ONLY";
        }

        // able to make change so update the coin arrays, products,  and purchasedItem
        $coinsToKeep = $coinsToKeepAndReturn['received'];
        $coinsToReturn = $coinsToKeepAndReturn['change'];

        // decrement item quantity
        $this->products[$item]->quantity--;

        // put purchased item in the tray
        $this->purchasedItem = $item;

        // move the coins to where they belong
        $this->coinBox = $coinsToKeep;
        $this->coinReturn = array_merge($this->coinReturn, $coinsToReturn);
        $this->coinCurrent = array();
        return "THANK YOU";
    }

    /**
     * cancel the current transaction and return coins
     *
     * @return void
     */
    public function returnCoins()
    {
        $this->coinReturn = array_merge($this->coinReturn, $this->coinCurrent);
        $this->coinCurrent = array();
    }

    /**
     * make change if possible
     *
     * return array(coinsToKeep, coinsToReturn) if able to make change
     * return null if unable to make change
     *
     * @SuppressWarnings(PHPMD.StaticAccess)
     * @return array(coins, coins)
     */
    private function makeChange($price)
    {
        $allCoins = array_merge($this->coinCurrent, $this->coinBox);
        $coinsToReturn = array();
        $coinsToKeep = array();

        $valueOfChangeAvail = 0;
        $valueOfChangeNeeded = $this->currentAmount() - $price;
        // partition all the coins into coins to keep and coins to return
        foreach (CoinArraySort::sortCoinsByValueDesc($allCoins) as $coin) {
            if ($valueOfChangeAvail + $coin->value() > $valueOfChangeNeeded) {
                $coinsToKeep[] = $coin;
                continue;
            }
            $valueOfChangeAvail += $coin->value();
            $coinsToReturn[] = $coin;
        }
        if ($valueOfChangeAvail != $valueOfChangeNeeded) {
            return null;
        }
        return array('received' => $coinsToKeep, 'change' => $coinsToReturn);
    }
}
