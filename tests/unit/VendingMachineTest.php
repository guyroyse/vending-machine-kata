<?php
namespace My;

// \Codeception\Util\Debug::debug("message");

class VendingMachineTest extends \Codeception\TestCase\Test
{
    /**
     * @var \UnitTester
     */
    protected $tester;
    private $vm;

    protected function _before()
    {
        $this->vm = new VendingMachine();
        $this->slug = new Coin();
        $this->nickel = new Coin(Coin::PROP_NICKEL);
        $this->dime = new Coin(Coin::PROP_DIME);
        $this->quarter = new Coin(Coin::PROP_QUARTER);
        //$this->nickel = new Coin(Coin::NICKEL['weight']-1, Coin::NICKEL['diameter']);
        //$this->dime = new Coin(Coin::DIME['weight']-1, Coin::DIME['diameter']);
        //$this->quarter = new Coin(Coin::QUARTER['weight']-1, Coin::QUARTER['diameter']);
    }

    protected function _after()
    {
    }

    private function currentAmount()
    {
        return CoinArrayValue::valueOfCoins($this->vm->coinCurrent);
    }

    private function coinBoxAmount()
    {
        return CoinArrayValue::valueOfCoins($this->vm->coinBox);
    }

    private function coinReturnAmount()
    {
        return CoinArrayValue::valueOfCoins($this->vm->coinReturn);
    }

    // tests
    public function testReturnsSlugs()
    {
        // insert 3 slugs and a quarter
        $this->vm->acceptCoin($this->slug);
        $this->vm->acceptCoin($this->slug);
        $this->vm->acceptCoin($this->slug);
        $this->vm->acceptCoin($this->quarter);
        // slugs should be in coin return
        $this->assertEquals(3, count($this->vm->coinReturn));
        // quarter should be in coins
        $this->assertEquals(1, count($this->vm->coinCurrent));
        $coins = $this->vm->coinReturn;
        // verify the slugs are all here
        $this->assertEquals($this->slug, $coins[0]);
        $this->assertEquals($this->slug, $coins[1]);
        $this->assertEquals($this->slug, $coins[2]);
        // and quarter too
        $this->assertEquals($this->quarter, $this->vm->coinCurrent[0]);
        // cancel should put all coins in coin return
        $this->vm->returnCoins();
        $this->assertEquals(4, count($this->vm->coinReturn));
        $this->assertEquals(0, count($this->vm->coinCurrent));
        $coins = $this->vm->coinReturn;
        // verify all coins in return
        $this->assertEquals($this->slug, $coins[0]);
        $this->assertEquals($this->slug, $coins[1]);
        $this->assertEquals($this->slug, $coins[2]);
        $this->assertEquals($this->quarter, $coins[3]);
    }

    public function testLoadCoinBox()
    {
        $this->vm->loadCoinBox(array($this->nickel, $this->dime, $this->quarter));
        $this->assertEquals(40, $this->coinBoxAmount());
        $this->vm->loadCoinBox(array($this->nickel, $this->dime, $this->quarter, $this->nickel, $this->dime, $this->quarter));
        $this->assertEquals(80, $this->coinBoxAmount());
    }

    public function testAcceptCoins()
    {
        $this->vm->acceptCoin($this->slug);
        $this->assertEquals(1, count($this->vm->coinReturn));
        $this->assertEquals("INSERT COIN", $this->vm->display());
        $this->assertEquals($this->slug, $this->vm->coinReturn[0]);

        $this->vm->acceptCoin($this->slug);
        $this->assertEquals(2, count($this->vm->coinReturn));
        $this->assertEquals("INSERT COIN", $this->vm->display());
        $this->assertEquals($this->slug, $this->vm->coinReturn[1]);

        $this->vm->acceptCoin($this->slug);
        $this->assertEquals(3, count($this->vm->coinReturn));
        $this->assertEquals("INSERT COIN", $this->vm->display());
        $this->assertEquals($this->slug, $this->vm->coinReturn[2]);

        $this->vm->acceptCoin($this->nickel);
        $this->assertEquals(3, count($this->vm->coinReturn));
        $this->assertEquals("$0.05", $this->vm->display());

        $this->vm->acceptCoin($this->dime);
        $this->assertEquals(3, count($this->vm->coinReturn));
        $this->assertEquals("$0.15", $this->vm->display());
    }

    public function testLoadProducts()
    {
        $this->vm->loadProduct(new Product("gum", 10, 1));
        $this->vm->loadProduct(new Product("cola", 100, 2));
        $this->vm->loadProduct(new Product("candy", 65, 3));
        $this->vm->loadProduct(new Product("cola", 100, 2));
        $this->assertEquals(1, $this->vm->products['gum']->quantity);
        $this->assertEquals(4, $this->vm->products['cola']->quantity);
        $this->assertEquals(3, $this->vm->products['candy']->quantity);
    }

    public function testSelectProduct()
    {
        $this->vm->loadProduct(new Product("cola", 100, 2));
        $this->vm->loadProduct(new Product("candy", 65, 3));
        $this->vm->loadProduct(new Product("steak", 999, 0));
        $this->vm->acceptCoin($this->quarter);
        $this->vm->acceptCoin($this->quarter);
        $this->vm->acceptCoin($this->quarter);
        $this->assertEquals(75, $this->currentAmount());
        $this->assertEquals(0, $this->coinReturnAmount());
        $this->assertEquals(0, $this->coinBoxAmount());
        $this->assertEquals("$0.75", $this->vm->display());
        $this->assertEquals("PRICE $1.00", $this->vm->select("cola"));
        $this->assertEquals("SOLD OUT", $this->vm->select("steak"));

        $this->vm->acceptCoin($this->quarter);
        $this->assertEquals(100, $this->currentAmount());
        $this->assertEquals("$1.00", $this->vm->display());
        $this->assertEquals("THANK YOU", $this->vm->select("cola"));
        $this->assertEquals(0, $this->coinReturnAmount());
        $this->assertEquals(100, $this->coinBoxAmount());
        $itemAndChange = $this->vm->takeItemAndChange();
        $this->assertEquals('cola', $itemAndChange['item']);
        $this->assertEquals(0, CoinArrayValue::valueOfCoins($itemAndChange['change']));
    }

    public function testSoldOut()
    {
        $this->vm->loadProduct(new Product("cola", 100, 0));
        $this->vm->acceptCoin($this->quarter);
        $this->assertEquals("SOLD OUT", $this->vm->select("cola"));
        $this->assertEquals(25, $this->currentAmount());
        $this->vm->returnCoins();
        $this->assertEquals(0, $this->currentAmount());
        $this->assertEquals(25, $this->coinReturnAmount());
    }

    public function testCancel()
    {
        $this->assertEquals("INSERT COIN", $this->vm->display());
        $this->vm->acceptCoin($this->nickel);
        $this->vm->acceptCoin($this->dime);
        $this->vm->acceptCoin($this->quarter);
        $this->assertEquals(40, $this->currentAmount());
        $this->assertEquals("$0.40", $this->vm->display());
        $this->assertEquals(0, $this->coinReturnAmount());
        $this->assertEquals(0, $this->coinBoxAmount());
        $this->vm->returnCoins();
        $this->assertEquals(0, $this->currentAmount());
        $this->assertEquals(40, $this->coinReturnAmount());
        $this->assertEquals(0, $this->coinBoxAmount());
        $this->assertEquals("INSERT COIN", $this->vm->display());
    }

    public function testMakeChangeSuccessWithoutUsingCoinBox()
    {
        // add products
        $this->vm->loadProduct(new Product("gum", 10, 1));
        $this->vm->loadProduct(new Product("cola", 100, 2));
        $this->vm->loadProduct(new Product("candy", 65, 3));

        // add coins
        $this->vm->acceptCoin($this->nickel);
        $this->vm->acceptCoin($this->nickel);
        $this->vm->acceptCoin($this->nickel);
        $this->vm->acceptCoin($this->dime);
        $this->vm->acceptCoin($this->dime);
        $this->vm->acceptCoin($this->dime);
        $this->vm->acceptCoin($this->quarter);
        $this->vm->acceptCoin($this->quarter);
        $this->vm->acceptCoin($this->quarter);
        $this->assertEquals(120, $this->currentAmount());
        $this->assertEquals(0, $this->coinReturnAmount());
        $this->assertEquals(0, $this->coinBoxAmount());

        // buy candy
        $this->assertEquals(3, $this->vm->products['candy']->quantity);
        $this->assertNull($this->vm->purchasedItem);
        $this->assertEquals(120, $this->currentAmount());
        $this->assertEquals("THANK YOU", $this->vm->select("candy"));
        $this->assertEquals(0, $this->currentAmount());
        $this->assertEquals(55, $this->coinReturnAmount());
        $this->assertEquals(65, $this->coinBoxAmount());
        $this->assertEquals(2, $this->vm->products['candy']->quantity);
        $itemAndChange = $this->vm->takeItemAndChange();
        $this->assertEquals('candy', $itemAndChange['item']);
        $this->assertEquals(55, CoinArrayValue::valueOfCoins($itemAndChange['change']));
        $this->assertEquals(65, $this->coinBoxAmount());
        $this->assertEquals(0, $this->coinReturnAmount());

        // buy last gum
        $this->vm->acceptCoin($this->quarter);
        $this->assertEquals(25, $this->currentAmount());
        $this->assertEquals(0, $this->coinReturnAmount());
        $this->assertEquals(65, $this->coinBoxAmount());
        $this->assertEquals("THANK YOU", $this->vm->select("gum"));
        $this->assertEquals(0, $this->currentAmount());
        $this->assertEquals(15, $this->coinReturnAmount());
        $this->assertEquals(75, $this->coinBoxAmount());
        $this->assertEquals(0, $this->vm->products['gum']->quantity);
        $itemAndChange = $this->vm->takeItemAndChange();
        $this->assertEquals('gum', $itemAndChange['item']);
        $this->assertEquals(15, CoinArrayValue::valueOfCoins($itemAndChange['change']));

        // try to buy more gum
        $this->vm->acceptCoin($this->quarter);
        $this->assertEquals(25, $this->currentAmount());
        $this->assertEquals(0, $this->coinReturnAmount());
        $this->assertEquals(75, $this->coinBoxAmount());
        $this->assertEquals("SOLD OUT", $this->vm->select("gum"));
        $this->assertEquals("$0.25", $this->vm->display());
        $this->assertEquals(25, $this->currentAmount());
        $this->assertEquals(0, $this->coinReturnAmount());
        $this->assertEquals(75, $this->coinBoxAmount());
        $this->assertEquals(0, $this->vm->products['gum']->quantity);
        $itemAndChange = $this->vm->takeItemAndChange();
        $this->assertNull($itemAndChange['item']);
        $this->assertEquals(0, CoinArrayValue::valueOfCoins($itemAndChange['change']));
        $this->assertEquals(0, $this->coinReturnAmount());
    }

    public function testExactChangeOnly()
    {
        $this->vm->acceptCoin($this->quarter);
        $this->vm->loadProduct(new Product("gum", 10, 1));
        // exact-change-only aborts the purchase and returns the coins to the user
        $this->assertEquals("EXACT CHANGE ONLY", $this->vm->select("gum"));
        $this->assertEquals("INSERT COIN", $this->vm->display());
        $this->assertEquals(0, $this->currentAmount());
        $this->assertEquals(25, $this->coinReturnAmount());
        $itemAndChange = $this->vm->takeItemAndChange();
        $this->assertNull($itemAndChange['item']);
        $this->assertEquals(25, CoinArrayValue::valueOfCoins($itemAndChange['change']));
        $this->assertEquals(0, $this->coinReturnAmount());
    }

    public function testMakeChangeSuccessUsingCoinBox()
    {
        // load coinbox
        $this->vm->loadCoinBox(array($this->nickel, $this->dime, $this->quarter));
        $this->assertEquals(40, $this->coinBoxAmount());

        // load gum
        $this->vm->loadProduct(new Product("gum", 10, 1));

        // buy gum
        $this->assertEquals("INSERT COIN", $this->vm->display());
        $this->vm->acceptCoin($this->quarter);
        $this->assertEquals(25, $this->currentAmount());
        $this->vm->acceptCoin($this->slug);
        $this->assertEquals(25, $this->currentAmount());
        $this->assertEquals("THANK YOU", $this->vm->select("gum"));
        $itemAndChange = $this->vm->takeItemAndChange();
        $this->assertEquals('gum', $itemAndChange['item']);
        $this->assertEquals(15, CoinArrayValue::valueOfCoins($itemAndChange['change']));
        $this->assertEquals(0, $this->coinReturnAmount());
        $this->assertEquals(50, $this->coinBoxAmount());
    }

    public function testNoSuchItem()
    {
        $this->vm->loadProduct(new Product("hairspray", 100, 1));
        $this->vm->acceptCoin($this->quarter);
        $this->assertEquals(25, $this->currentAmount());
        $this->assertEquals("NO SUCH ITEM", $this->vm->select("paint"));
        $this->assertEquals(25, $this->currentAmount());
    }
}
