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
    }

    protected function _after()
    {
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
        $this->assertEquals(3, count($this->vm->coinReturn->all()));
        // quarter should be in coins
        $this->assertEquals(1, count($this->vm->coinCurrent->all()));
        $coins = $this->vm->coinReturn->all();
        // verify the slugs are all here
        $this->assertEquals($this->slug, $coins[0]);
        $this->assertEquals($this->slug, $coins[1]);
        $this->assertEquals($this->slug, $coins[2]);
        // and quarter too
        $this->assertEquals($this->quarter, $this->vm->coinCurrent->all()[0]);
        // cancel should put all coins in coin return
        $this->vm->returnCoins();
        $this->assertEquals(4, count($this->vm->coinReturn->all()));
        $this->assertEquals(0, count($this->vm->coinCurrent->all()));
        $coins = $this->vm->coinReturn->all();
        // verify all coins in return
        $this->assertEquals($this->slug, $coins[0]);
        $this->assertEquals($this->slug, $coins[1]);
        $this->assertEquals($this->slug, $coins[2]);
        $this->assertEquals($this->quarter, $coins[3]);
    }

    public function testLoadCoinBox()
    {
        $this->vm->loadCoinBox(array($this->nickel, $this->dime, $this->quarter));
        $this->assertEquals(40, $this->vm->coinBox->value());
        $this->vm->loadCoinBox(array($this->nickel, $this->dime, $this->quarter, $this->nickel, $this->dime, $this->quarter));
        $this->assertEquals(80, $this->vm->coinBox->value());
    }

    public function testAcceptCoins()
    {
        $this->vm->acceptCoin($this->slug);
        $this->assertEquals(1, count($this->vm->coinReturn->all()));
        $this->assertEquals("INSERT COIN", $this->vm->display());
        $this->assertEquals($this->slug, $this->vm->coinReturn->all()[0]);

        $this->vm->acceptCoin($this->slug);
        $this->assertEquals(2, count($this->vm->coinReturn->all()));
        $this->assertEquals("INSERT COIN", $this->vm->display());
        $this->assertEquals($this->slug, $this->vm->coinReturn->all()[1]);

        $this->vm->acceptCoin($this->slug);
        $this->assertEquals(3, count($this->vm->coinReturn->all()));
        $this->assertEquals("INSERT COIN", $this->vm->display());
        $this->assertEquals($this->slug, $this->vm->coinReturn->all()[2]);

        $this->vm->acceptCoin($this->nickel);
        $this->assertEquals(3, count($this->vm->coinReturn->all()));
        $this->assertEquals("$0.05", $this->vm->display());

        $this->vm->acceptCoin($this->dime);
        $this->assertEquals(3, count($this->vm->coinReturn->all()));
        $this->assertEquals("$0.15", $this->vm->display());
    }

    public function testLoadProducts()
    {
        $this->vm->products->load(new Product("gum", 10, 1));
        $this->vm->products->load(new Product("cola", 100, 2));
        $this->vm->products->load(new Product("candy", 65, 3));
        $this->vm->products->load(new Product("cola", 100, 2));
        $this->assertEquals(1, $this->vm->products->get('gum')->quantity);
        $this->assertEquals(4, $this->vm->products->get('cola')->quantity);
        $this->assertEquals(3, $this->vm->products->get('candy')->quantity);
    }

    public function testSelectProduct()
    {
        $this->vm->products->load(new Product("cola", 100, 2));
        $this->vm->products->load(new Product("candy", 65, 3));
        $this->vm->products->load(new Product("steak", 999, 0));
        $this->vm->acceptCoin($this->quarter);
        $this->vm->acceptCoin($this->quarter);
        $this->vm->acceptCoin($this->quarter);
        $this->assertEquals(75, $this->vm->coinCurrent->value());
        $this->assertEquals(0, $this->vm->coinReturn->value());
        $this->assertEquals(0, $this->vm->coinBox->value());
        $this->assertEquals("$0.75", $this->vm->display());
        $this->assertEquals("PRICE $1.00", $this->vm->select("cola"));
        $this->assertEquals("SOLD OUT", $this->vm->select("steak"));

        $this->vm->acceptCoin($this->quarter);
        $this->assertEquals(100, $this->vm->coinCurrent->value());
        $this->assertEquals("$1.00", $this->vm->display());
        $this->assertEquals("THANK YOU", $this->vm->select("cola"));
        $this->assertEquals(0, $this->vm->coinReturn->value());
        $this->assertEquals(100, $this->vm->coinBox->value());
        $itemAndChange = $this->vm->takeItemAndChange();
        $this->assertEquals('cola', $itemAndChange['item']);
        $this->assertEquals(0, $itemAndChange['change']->value());
    }

    public function testSoldOut()
    {
        $this->vm->products->load(new Product("cola", 100, 0));
        $this->vm->acceptCoin($this->quarter);
        $this->assertEquals("SOLD OUT", $this->vm->select("cola"));
        $this->assertEquals(25, $this->vm->coinCurrent->value());
        $this->vm->returnCoins();
        $this->assertEquals(0, $this->vm->coinCurrent->value());
        $this->assertEquals(25, $this->vm->coinReturn->value());
    }

    public function testCancel()
    {
        $this->assertEquals("INSERT COIN", $this->vm->display());
        $this->vm->acceptCoin($this->nickel);
        $this->vm->acceptCoin($this->dime);
        $this->vm->acceptCoin($this->quarter);
        $this->assertEquals(40, $this->vm->coinCurrent->value());
        $this->assertEquals("$0.40", $this->vm->display());
        $this->assertEquals(0, $this->vm->coinReturn->value());
        $this->assertEquals(0, $this->vm->coinBox->value());
        $this->vm->returnCoins();
        $this->assertEquals(0, $this->vm->coinCurrent->value());
        $this->assertEquals(40, $this->vm->coinReturn->value());
        $this->assertEquals(0, $this->vm->coinBox->value());
        $this->assertEquals("INSERT COIN", $this->vm->display());
    }

    public function testMakeChangeSuccessWithoutUsingCoinBox()
    {
        // add products
        $this->vm->products->load(new Product("gum", 10, 1));
        $this->vm->products->load(new Product("cola", 100, 2));
        $this->vm->products->load(new Product("candy", 65, 3));

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
        $this->assertEquals(120, $this->vm->coinCurrent->value());
        $this->assertEquals(0, $this->vm->coinReturn->value());
        $this->assertEquals(0, $this->vm->coinBox->value());

        // buy candy
        $this->assertEquals(3, $this->vm->products->get('candy')->quantity);
        $this->assertNull($this->vm->purchasedItem);
        $this->assertEquals(120, $this->vm->coinCurrent->value());
        $this->assertEquals("THANK YOU", $this->vm->select("candy"));
        $this->assertEquals(0, $this->vm->coinCurrent->value());
        $this->assertEquals(55, $this->vm->coinReturn->value());
        $this->assertEquals(65, $this->vm->coinBox->value());
        $this->assertEquals(2, $this->vm->products->get('candy')->quantity);
        $itemAndChange = $this->vm->takeItemAndChange();
        $this->assertEquals('candy', $itemAndChange['item']);
        $this->assertEquals(55, $itemAndChange['change']->value());
        $this->assertEquals(65, $this->vm->coinBox->value());
        $this->assertEquals(0, $this->vm->coinReturn->value());

        // buy last gum
        $this->vm->acceptCoin($this->quarter);
        $this->assertEquals(25, $this->vm->coinCurrent->value());
        $this->assertEquals(0, $this->vm->coinReturn->value());
        $this->assertEquals(65, $this->vm->coinBox->value());
        $this->assertEquals("THANK YOU", $this->vm->select("gum"));
        $this->assertEquals(0, $this->vm->coinCurrent->value());
        $this->assertEquals(15, $this->vm->coinReturn->value());
        $this->assertEquals(75, $this->vm->coinBox->value());
        $this->assertEquals(0, $this->vm->products->get('gum')->quantity);
        $itemAndChange = $this->vm->takeItemAndChange();
        $this->assertEquals('gum', $itemAndChange['item']);
        $this->assertEquals(15, $itemAndChange['change']->value());

        // try to buy more gum
        $this->vm->acceptCoin($this->quarter);
        $this->assertEquals(25, $this->vm->coinCurrent->value());
        $this->assertEquals(0, $this->vm->coinReturn->value());
        $this->assertEquals(75, $this->vm->coinBox->value());
        $this->assertEquals("SOLD OUT", $this->vm->select("gum"));
        $this->assertEquals("$0.25", $this->vm->display());
        $this->assertEquals(25, $this->vm->coinCurrent->value());
        $this->assertEquals(0, $this->vm->coinReturn->value());
        $this->assertEquals(75, $this->vm->coinBox->value());
        $this->assertEquals(0, $this->vm->products->get('gum')->quantity);
        $itemAndChange = $this->vm->takeItemAndChange();
        $this->assertNull($itemAndChange['item']);
        $this->assertEquals(0, $itemAndChange['change']->value());
        $this->assertEquals(0, $this->vm->coinReturn->value());
    }

    public function testExactChangeOnly()
    {
        $this->vm->acceptCoin($this->quarter);
        $this->vm->products->load(new Product("gum", 10, 1));
        // exact-change-only aborts the purchase and returns the coins to the user
        $this->assertEquals("EXACT CHANGE ONLY", $this->vm->select("gum"));
        $this->assertEquals("INSERT COIN", $this->vm->display());
        $this->assertEquals(0, $this->vm->coinCurrent->value());
        $this->assertEquals(25, $this->vm->coinReturn->value());
        $itemAndChange = $this->vm->takeItemAndChange();
        $this->assertNull($itemAndChange['item']);
        $this->assertEquals(25, $itemAndChange['change']->value());
        $this->assertEquals(0, $this->vm->coinReturn->value());
    }

    public function testMakeChangeSuccessUsingCoinBox()
    {
        // load coinbox
        $this->vm->loadCoinBox(array($this->nickel, $this->dime, $this->quarter));
        $this->assertEquals(40, $this->vm->coinBox->value());

        // load gum
        $this->vm->products->load(new Product("gum", 10, 1));

        // buy gum
        $this->assertEquals("INSERT COIN", $this->vm->display());
        $this->vm->acceptCoin($this->quarter);
        $this->assertEquals(25, $this->vm->coinCurrent->value());
        $this->vm->acceptCoin($this->slug);
        $this->assertEquals(25, $this->vm->coinCurrent->value());
        $this->assertEquals("THANK YOU", $this->vm->select("gum"));
        $itemAndChange = $this->vm->takeItemAndChange();
        $this->assertEquals('gum', $itemAndChange['item']);
        $this->assertEquals(15, $itemAndChange['change']->value());
        $this->assertEquals(0, $this->vm->coinReturn->value());
        $this->assertEquals(50, $this->vm->coinBox->value());
    }

    public function testNoSuchItem()
    {
        $this->vm->products->load(new Product("hairspray", 100, 1));
        $this->vm->acceptCoin($this->quarter);
        $this->assertEquals(25, $this->vm->coinCurrent->value());
        $this->assertEquals("NO SUCH ITEM", $this->vm->select("paint"));
        $this->assertEquals(25, $this->vm->coinCurrent->value());
    }
}
