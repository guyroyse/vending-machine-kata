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
        $product = (object) null;
        $coinsToKeepAndReturn = array();
        $selector = null;

        // find the product
        $product = Product::get($this->products, $item);

        if (is_null($product)) {
            //return $this->selectNoSuchItem();
            $product = new Product('_', 0);
            $selector = new SelectNoSuchItem;
        } elseif ($product->quantity <= 0) {
            //return $this->selectSoldOut();
            $selector = new SelectSoldOut;
        } elseif ($this->coinCurrent->value() < $product->price) {
            //return $this->selectInsufficientFunds($product);
            $selector = new SelectInsufficientFunds;
        } elseif (is_null($coinsToKeepAndReturn = ChangeMaker::makeChange($product->price, $this->coinCurrent, $this->coinBox))) {
            //return $this->selectExactChangeOnly();
            $coinsToKeepAndReturn = array();
            $selector = new SelectExactChangeOnly;
        } else {
            // able to make change so update the coin arrays, products, and purchasedItem
            $selector = new SelectThankYou;
        }
        return $selector->select($this, $product, $coinsToKeepAndReturn);
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

    /**
     * Update Coin Containers after successful purchase
     *
     * @param $coinsToKeep
     * @param $coinsToReturn
     */
    public function updateCoinContainers($coinsToKeep, $coinsToReturn)
    {
        $this->coinBox = $coinsToKeep;
        $this->coinReturn = $this->coinReturn->merge($coinsToReturn);
        $this->coinCurrent = new CoinCollection();
    }

    /**
     * Update products after successful purchase
     *
     * @param $item
     */
    public function updateProducts($item)
    {
        // decrement item quantity
        $this->products[$item]->quantity--;
        // put purchased item in the tray
        $this->purchasedItem = $item;
    }
}
