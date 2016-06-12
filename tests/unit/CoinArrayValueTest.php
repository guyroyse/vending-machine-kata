<?php
namespace My;

class CoinArrayValueTest extends \Codeception\TestCase\Test
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
    public function testValueOfCoins()
    {
        $this->assertEquals(5, CoinArrayValue::valueOfCoins(array($this->nickel)));
        $this->assertEquals(10, CoinArrayValue::valueOfCoins(array($this->dime)));
        $this->assertEquals(15, CoinArrayValue::valueOfCoins(array($this->nickel, $this->dime)));
        $this->assertEquals(40, CoinArrayValue::valueOfCoins(array($this->nickel, $this->dime, $this->quarter)));
        $this->assertEquals(40, CoinArrayValue::valueOfCoins(array($this->slug, $this->nickel, $this->dime, $this->quarter)));
    }
}
