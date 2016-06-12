<?php
namespace My;

class CoinArraySortTest extends \Codeception\TestCase\Test
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
    public function testSortCoinsByValueDesc()
    {
        $expected = array($this->quarter, $this->dime, $this->nickel);
        $try1 = array($this->dime, $this->quarter, $this->nickel);
        $this->assertEquals($expected, CoinArraySort::sortCoinsByValueDesc($try1));
        $try2 = array($this->quarter, $this->nickel, $this->dime);
        $this->assertEquals($expected, CoinArraySort::sortCoinsByValueDesc($try2));
        $try3 = array($this->dime, $this->nickel, $this->quarter);
        $this->assertEquals($expected, CoinArraySort::sortCoinsByValueDesc($try3));
    }
}
