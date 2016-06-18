<?php
namespace My;

// \Codeception\Util\Debug::debug("message");

class ChangeMakerTest extends \Codeception\TestCase\Test
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
    public function testNoCoinsAtAll()
    {
        $res = ChangeMaker::makeChange(25, array(), array());
        $this->assertNull($res);
    }
    public function testNoCoinsInCoinBox()
    {
        $res = ChangeMaker::makeChange(10, array($this->quarter), array());
        $this->assertNull($res);
    }
    public function testInsertQuarterReturnDimeAndNickel()
    {
        $res = ChangeMaker::makeChange(10, array($this->quarter), array($this->nickel, $this->dime));
        $this->assertEquals(array($this->quarter), $res['received']);
        $this->assertEquals(array($this->dime, $this->nickel), $res['change']);
    }
    public function testInsertTwoDimesReturnDime()
    {
        $res = ChangeMaker::makeChange(10, array($this->dime, $this->dime), array($this->nickel, $this->dime));
        $this->assertEquals(array($this->dime, $this->dime, $this->nickel), $res['received']);
        $this->assertEquals(array($this->dime), $res['change']);
    }
}
