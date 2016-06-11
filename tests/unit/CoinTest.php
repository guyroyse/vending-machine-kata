<?php
namespace My;

class CoinTest extends \Codeception\TestCase\Test
{
    /**
     * @var \UnitTester
     */
    protected $tester;

    protected function _before()
    {
        $this->slug = new Coin();
        $this->nickel = new Coin(Coin::PROP_NICKEL);
        $this->dime = new Coin(Coin::PROP_DIME);
        $this->quarter = new Coin(Coin::PROP_QUARTER);
    }

    protected function _after()
    {
    }

    // tests
    public function testInvlid()
    {
        $this->assertEquals(0, $this->slug->value());
    }

    public function testNickel()
    {
        $this->assertEquals(Coin::TYPE_NICKEL['value'], $this->nickel->value());
    }

    public function testDime()
    {
        $this->assertEquals(Coin::TYPE_DIME['value'], $this->dime->value());
    }

    public function testQuarter()
    {
        $this->assertEquals(Coin::TYPE_QUARTER['value'], $this->quarter->value());
    }

    public function testValueOfCoins()
    {
        $this->assertEquals(5, Coin::valueOfCoins(array($this->nickel)));
        $this->assertEquals(10, Coin::valueOfCoins(array($this->dime)));
        $this->assertEquals(15, Coin::valueOfCoins(array($this->nickel, $this->dime)));
        $this->assertEquals(40, Coin::valueOfCoins(array($this->nickel, $this->dime, $this->quarter)));
        $this->assertEquals(40, Coin::valueOfCoins(array($this->slug, $this->nickel, $this->dime, $this->quarter)));
    }

    public function testSortCoinsByValueDesc()
    {
        $expected = array($this->quarter, $this->dime, $this->nickel);
        $try1 = array($this->dime, $this->quarter, $this->nickel);
        $this->assertEquals($expected, Coin::sortCoinsByValueDesc($try1));
        $try2 = array($this->quarter, $this->nickel, $this->dime);
        $this->assertEquals($expected, Coin::sortCoinsByValueDesc($try2));
        $try3 = array($this->dime, $this->nickel, $this->quarter);
        $this->assertEquals($expected, Coin::sortCoinsByValueDesc($try3));
    }
}
