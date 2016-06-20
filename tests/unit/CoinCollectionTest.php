<?php
namespace My;

class CoinCollectionTest extends \Codeception\TestCase\Test
{
    /**
     * @var \UnitTester
     */
    protected $tester;

    protected function _before()
    {
        $this->coins = new CoinCollection();
        $this->slug = new Coin();
        $this->nickel = new Coin(Coin::PROP_NICKEL);
        $this->dime = new Coin(Coin::PROP_DIME);
        $this->quarter = new Coin(Coin::PROP_QUARTER);
    }

    protected function _after()
    {
    }

    // tests
    public function testEmptyCollectionHasValueZero()
    {
        $this->assertEquals(0, $this->coins->value());
    }
    public function testConstructor()
    {
        $coins = new CoinCollection(array($this->nickel, $this->dime, $this->quarter, $this->slug));
        $this->assertEquals(array($this->nickel, $this->dime, $this->quarter, $this->slug), $coins->all());
        $this->assertEquals(40, $coins->value());
    }
    public function testValueOfCollection()
    {
        $this->coins->push($this->nickel);
        $this->assertEquals(5, $this->coins->value());
        $this->coins->push($this->dime);
        $this->assertEquals(15, $this->coins->value());
        $this->coins->push($this->quarter);
        $this->assertEquals(40, $this->coins->value());
        $this->coins->push($this->slug);
        $this->assertEquals(40, $this->coins->value());
    }
    public function testGetArrayOfCoinsFromCollection()
    {
        $this->coins->push($this->nickel);
        $this->coins->push($this->dime);
        $this->coins->push($this->quarter);
        $this->coins->push($this->slug);
        $this->assertEquals(array($this->nickel, $this->dime, $this->quarter, $this->slug), $this->coins->all());
    }
    public function testSortByValueDescReturnsSortedCollection()
    {
        $this->coins->push($this->nickel);
        $this->coins->push($this->dime);
        $this->coins->push($this->quarter);
        $this->coins->push($this->slug);
        $this->assertEquals(array($this->quarter, $this->dime, $this->nickel, $this->slug), $this->coins->sortByValueDesc()->all());
    }
    public function testMergeReturnsNewMergedCollection()
    {
        $this->coins->push($this->nickel);
        $this->coins->push($this->dime);
        $this->coins->push($this->quarter);
        $this->coins->push($this->slug);
        $this->assertEquals(40, $this->coins->value());
        $other = new CoinCollection(array($this->quarter, $this->dime));
        $this->assertEquals(35, $other->value());
        $merged = $this->coins->merge($other);
        $this->assertEquals(40, $this->coins->value());
        $this->assertEquals(35, $other->value());
        $this->assertEquals(75, $merged->value());
    }
}
