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
    public $coinBox; // CoinCollection of the machine's coin box
    public $coinCurrent; // CoinCollection of coins inserted by current customer
    public $coinReturn; // CoinCollection to be returned to current customer
    public $products; // array of Product in machine
    public $purchasedItem; // string

    public function __construct()
    {
        $this->coinBox = new CoinCollection();
        $this->coinCurrent = new CoinCollection();
        $this->coinReturn = new CoinCollection();
        $this->products = array();
        $this->purchasedItem = null;
    }

    /**
     * take the purchased item and change
     *
     * @return int array(item, change)
     */
    public function takeItemAndChange()
    {
        $change = $this->coinReturn;
        $this->coinReturn = new CoinCollection();
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
            $this->coinReturn->push($coin);
            return;
        }
        $this->coinCurrent->push($coin);
    }

    /**
     * pre-load the coinbox
     *
     * @return void
     */
    public function loadCoinBox(array $coins)
    {
        $this->coinBox = new CoinCollection($coins);
    }

    /**
     * display either "INSERT COIN" or the current amount of inserted coins
     *
     * @return string
     */
    public function display()
    {
        $tot = $this->coinCurrent->value();
        return $tot == 0 ? "INSERT COIN" : sprintf("$%0.2f", $tot / 100);
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
     *
     * @SuppressWarnings(PHPMD.StaticAccess)
     * @return string
     */
    public function select($item)
    {
        $product = null;

        // find the product
/*
        $pxx = array_filter($this->products, function ($ptmp) use ($item) {
            return $ptmp->name == $item ? $ptmp : null;
        });
        // set product to first elem of pxx or null if pxx is empty
        $product = array_shift($pxx);
*/
        $product = Product::get($this->products, $item);

        if (is_null($product)) {
            return "NO SUCH ITEM";
        }

        if ($product->quantity <= 0) {
            return "SOLD OUT";
        }

        if ($this->coinCurrent->value() < $product->price) {
            return "PRICE ".sprintf("$%0.2f", $product->price / 100);
        }

        // attempt to make change
        if (is_null($coinsToKeepAndReturn = ChangeMaker::makeChange($product->price, $this->coinCurrent, $this->coinBox))) {
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
        $this->coinReturn = $this->coinReturn->merge($coinsToReturn);
        $this->coinCurrent = new CoinCollection();
        return "THANK YOU";
    }

    /**
     * cancel the current transaction and return coins
     *
     * @return void
     */
    public function returnCoins()
    {
        $this->coinReturn = $this->coinReturn->merge($this->coinCurrent);
        $this->coinCurrent = new CoinCollection();
    }
}
