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
}
