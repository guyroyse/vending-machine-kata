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
    }

    protected function _after()
    {
    }

    // tests
    public function testInvlid()
    {
        $coin = new Coin();
        $this->assertEquals(-1, $coin->value());
    }

    public function testNickle()
    {
        $coin = new Coin(5,5);
        $this->assertEquals(5, $coin->value(Coin::NICKLE[0],Coin::NICKLE[1]));
    }

    public function testDime()
    {
        $coin = new Coin(10,10);
        $this->assertEquals(10, $coin->value(Coin::DIME[0],Coin::DIME[1]));
    }

    public function testQuarter()
    {
        $coin = new Coin(25,25);
        $this->assertEquals(25, $coin->value(Coin::QUARTER[0],Coin::QUARTER[1]));
    }
}
