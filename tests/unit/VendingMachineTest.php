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
    }

    protected function _after()
    {
    }

    // tests
    public function testReturnsSlugs()
    {
        $c05x = new Coin(5, 4);
        $c10x = new Coin(9, 10);
        $c25x = new Coin(24, 26);
        $c25 = new Coin(25, 25);
        // insert 3 slugs and a quarter
        $this->vm->acceptCoin($c05x);
        $this->vm->acceptCoin($c10x);
        $this->vm->acceptCoin($c25x);
        $this->vm->acceptCoin($c25);
        // slugs should be in coin return
        $this->assertEquals(3, count($this->vm->coinReturn));
        // quarter should be in coins
        $this->assertEquals(1, count($this->vm->coins));
        $coins = $this->vm->coinReturn;
        sort($coins);
        // verify the slugs are all here
        $this->assertEquals($c05x, $coins[0]);
        $this->assertEquals($c10x, $coins[1]);
        $this->assertEquals($c25x, $coins[2]);
        // and quarter too
        $this->assertEquals($c25, $this->vm->coins[0]);
        // cancel should put all coins in coin return
        $this->vm->cancel();
        $this->assertEquals(4, count($this->vm->coinReturn));
        $this->assertEquals(0, count($this->vm->coins));
        $coins = $this->vm->coinReturn;
        sort($coins);
        // verify all coins in return
        $this->assertEquals($c05x, $coins[0]);
        $this->assertEquals($c10x, $coins[1]);
        $this->assertEquals($c25x, $coins[2]);
        $this->assertEquals($c25, $coins[3]);
    }

    public function testLoadCoinBox()
    {
        $c05 = new Coin(5, 5);
        $c10 = new Coin(10, 10);
        $c25 = new Coin(25, 25);
        $this->vm->loadCoinBox(array($c05, $c10, $c25));
        $this->assertEquals(40, $this->vm->coinBoxAmount());
        $this->vm->loadCoinBox(array($c05, $c10, $c25, $c05, $c10, $c25));
        $this->assertEquals(80, $this->vm->coinBoxAmount());
    }

    public function testAcceptCoins()
    {
        $this->vm->acceptCoin(new Coin());
        $this->assertEquals(1, count($this->vm->coinReturn));
        $this->assertEquals("INSERT COIN", $this->vm->display());
        $this->assertEquals(new Coin(), $this->vm->coinReturn[0]);

        $this->vm->acceptCoin(new Coin(5, 0));
        $this->assertEquals(2, count($this->vm->coinReturn));
        $this->assertEquals("INSERT COIN", $this->vm->display());
        $this->assertEquals(new Coin(5, 0), $this->vm->coinReturn[1]);

        $this->vm->acceptCoin(new Coin(3, 3));
        $this->assertEquals(3, count($this->vm->coinReturn));
        $this->assertEquals("INSERT COIN", $this->vm->display());
        $this->assertEquals(new Coin(3, 3), $this->vm->coinReturn[2]);

        $this->vm->acceptCoin(new Coin(5, 5));
        $this->assertEquals(3, count($this->vm->coinReturn));
        $this->assertEquals("$0.05", $this->vm->display());

        $this->vm->acceptCoin(new Coin(10, 10));
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
        $this->vm->acceptCoin(new Coin(25, 25));
        $this->vm->acceptCoin(new Coin(25, 25));
        $this->vm->acceptCoin(new Coin(25, 25));
        $this->assertEquals(75, $this->vm->currentAmount());
        $this->assertEquals(0, $this->vm->coinReturnAmount());
        $this->assertEquals(0, $this->vm->coinBoxAmount());
        $this->assertEquals("$0.75", $this->vm->display());
        $this->assertEquals("PRICE $1.00", $this->vm->select("cola"));
        $this->assertEquals("SOLD OUT", $this->vm->select("steak"));

        $this->vm->acceptCoin(new Coin(25, 25));
        $this->assertEquals(100, $this->vm->currentAmount());
        $this->assertEquals("$1.00", $this->vm->display());
        $this->assertEquals("THANK YOU", $this->vm->select("cola"));
        $this->assertEquals(0, $this->vm->coinReturnAmount());
        $this->assertEquals(100, $this->vm->coinBoxAmount());
        $itemAndChange = $this->vm->takeItemAndChange();
        $this->assertEquals('cola', $itemAndChange[0]);
        $this->assertEquals(0, $this->vm->calcCoinAmount($itemAndChange[1]));
    }

    public function testSoldOut()
    {
        $this->vm->loadProduct(new Product("cola", 100, 0));
        $this->vm->acceptCoin(new Coin(25, 25));
        $this->assertEquals("SOLD OUT", $this->vm->select("cola"));
        $this->assertEquals(25, $this->vm->currentAmount());
        $this->vm->cancel();
        $this->assertEquals(0, $this->vm->currentAmount());
        $this->assertEquals(25, $this->vm->coinReturnAmount());
    }

    public function testCancel()
    {
        $this->assertEquals("INSERT COIN", $this->vm->display());
        $this->vm->acceptCoin(new Coin(5, 5));
        $this->vm->acceptCoin(new Coin(10, 10));
        $this->vm->acceptCoin(new Coin(25, 25));
        $this->assertEquals(40, $this->vm->currentAmount());
        $this->assertEquals("$0.40", $this->vm->display());
        $this->assertEquals(0, $this->vm->coinReturnAmount());
        $this->assertEquals(0, $this->vm->coinBoxAmount());
        $this->vm->cancel();
        $this->assertEquals(0, $this->vm->currentAmount());
        $this->assertEquals(40, $this->vm->coinReturnAmount());
        $this->assertEquals(0, $this->vm->coinBoxAmount());
        $this->assertEquals("INSERT COIN", $this->vm->display());
    }

    public function testMakeChangeSuccessWithoutUsingCoiBox()
    {
        // add products
        $this->vm->loadProduct(new Product("gum", 10, 1));
        $this->vm->loadProduct(new Product("cola", 100, 2));
        $this->vm->loadProduct(new Product("candy", 65, 3));

        // add coins
        $this->vm->acceptCoin(new Coin(5, 5));
        $this->vm->acceptCoin(new Coin(5, 5));
        $this->vm->acceptCoin(new Coin(5, 5));
        $this->vm->acceptCoin(new Coin(10, 10));
        $this->vm->acceptCoin(new Coin(10, 10));
        $this->vm->acceptCoin(new Coin(10, 10));
        $this->vm->acceptCoin(new Coin(25, 25));
        $this->vm->acceptCoin(new Coin(25, 25));
        $this->vm->acceptCoin(new Coin(25, 25));
        $this->assertEquals(120, $this->vm->currentAmount());
        $this->assertEquals(0, $this->vm->coinReturnAmount());
        $this->assertEquals(0, $this->vm->coinBoxAmount());

        // buy candy
        $this->assertEquals(3, $this->vm->products['candy']->quantity);
        $this->assertNull($this->vm->purchasedItem);
        $this->assertEquals(120, $this->vm->currentAmount());
        $this->assertEquals("THANK YOU", $this->vm->select("candy"));
        $this->assertEquals(0, $this->vm->currentAmount());
        $this->assertEquals(55, $this->vm->coinReturnAmount());
        $this->assertEquals(65, $this->vm->coinBoxAmount());
        $this->assertEquals(2, $this->vm->products['candy']->quantity);
        $itemAndChange = $this->vm->takeItemAndChange();
        $this->assertEquals('candy', $itemAndChange[0]);
        $this->assertEquals(55, $this->vm->calcCoinAmount($itemAndChange[1]));
        $this->assertEquals(65, $this->vm->coinBoxAmount());
        $this->assertEquals(0, $this->vm->coinReturnAmount());

        // buy last gum
        $this->vm->acceptCoin(new Coin(25, 25));
        $this->assertEquals(25, $this->vm->currentAmount());
        $this->assertEquals(0, $this->vm->coinReturnAmount());
        $this->assertEquals(65, $this->vm->coinBoxAmount());
        $this->assertEquals("THANK YOU", $this->vm->select("gum"));
        $this->assertEquals(0, $this->vm->currentAmount());
        $this->assertEquals(15, $this->vm->coinReturnAmount());
        $this->assertEquals(75, $this->vm->coinBoxAmount());
        $this->assertEquals(0, $this->vm->products['gum']->quantity);
        $itemAndChange = $this->vm->takeItemAndChange();
        $this->assertEquals('gum', $itemAndChange[0]);
        $this->assertEquals(15, $this->vm->calcCoinAmount($itemAndChange[1]));

        // try to buy more gum
        $this->vm->acceptCoin(new Coin(25, 25));
        $this->assertEquals(25, $this->vm->currentAmount());
        $this->assertEquals(0, $this->vm->coinReturnAmount());
        $this->assertEquals(75, $this->vm->coinBoxAmount());
        $this->assertEquals("SOLD OUT", $this->vm->select("gum"));
        $this->assertEquals("$0.25", $this->vm->display());
        $this->assertEquals(25, $this->vm->currentAmount());
        $this->assertEquals(0, $this->vm->coinReturnAmount());
        $this->assertEquals(75, $this->vm->coinBoxAmount());
        $this->assertEquals(0, $this->vm->products['gum']->quantity);
        $itemAndChange = $this->vm->takeItemAndChange();
        $this->assertNull($itemAndChange[0]);
        $this->assertEquals(0, $this->vm->calcCoinAmount($itemAndChange[1]));
        $this->assertEquals(0, $this->vm->coinReturnAmount());
    }

    public function testExactChangeOnly()
    {
        $this->vm->acceptCoin(new Coin(25, 25));
        $this->vm->loadProduct(new Product("gum", 10, 1));
        // exact-change-only aborts the purchase and returns the coins to the user
        $this->assertEquals("EXACT CHANGE ONLY", $this->vm->select("gum"));
        $this->assertEquals("INSERT COIN", $this->vm->display());
        $this->assertEquals(0, $this->vm->currentAmount());
        $this->assertEquals(25, $this->vm->coinReturnAmount());
        $itemAndChange = $this->vm->takeItemAndChange();
        $this->assertNull($itemAndChange[0]);
        $this->assertEquals(25, $this->vm->calcCoinAmount($itemAndChange[1]));
        $this->assertEquals(0, $this->vm->coinReturnAmount());
    }

    public function testMakeChangeSuccessUsingCoiBox()
    {
        // load coinbox
        $c05 = new Coin(5, 5);
        $c10 = new Coin(10, 10);
        $c25 = new Coin(25, 25);
        $this->vm->loadCoinBox(array($c05, $c10, $c25));
        $this->assertEquals(40, $this->vm->coinBoxAmount());

        // load gum
        $this->vm->loadProduct(new Product("gum", 10, 1));

        // buy gum
        $this->assertEquals("INSERT COIN", $this->vm->display());
        $this->vm->acceptCoin(new Coin(25, 25));
        $this->assertEquals("THANK YOU", $this->vm->select("gum"));
        $itemAndChange = $this->vm->takeItemAndChange();
        $this->assertEquals('gum', $itemAndChange[0]);
        $this->assertEquals(15, $this->vm->calcCoinAmount($itemAndChange[1]));
        $this->assertEquals(0, $this->vm->coinReturnAmount());
        $this->assertEquals(50, $this->vm->coinBoxAmount());
    }

    public function testNoSuchItem()
    {
        $this->vm->acceptCoin(new Coin(25, 25));
        $this->assertEquals(25, $this->vm->currentAmount());
        $this->assertEquals("NO SUCH ITEM", $this->vm->select("paint"));
        $this->assertEquals(25, $this->vm->currentAmount());
    }
}
