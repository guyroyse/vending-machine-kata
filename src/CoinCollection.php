<?php
namespace My;

/**
 * A CoinCollectiton is a collection of coins of any type including slug.
 * The underlying data structure is a simple array of Coin objects.
 *
 * A CoinCollection can be initialized via the constructor given an array of Coin objects.
 * all() returns the underlying array of Coin objects.
 * push($coin) adds the new Coin to the end of the array.
 * sortByValueDesc() sorts a CoinCollection by Coin value descending.
 * merge($other) merges this CoinCollection with the other CoinCollection returning a new CoinCollection.
 * value() returns the value of a CoinCollection.
 *
 */
class CoinCollection
{
    /** @var array of Coin $coins */
    private $coins;

    public function __construct(array $coins = array())
    {
        $this->coins = $coins;
    }

    /**
     * Return the collection
     *
     * @return array of Coin
     */
    public function all()
    {
        return $this->coins;
    }

    /**
     * Merge another collection with this collection
     * Create a new collection that is the result of the merge
     * Do not modify the original collection
     *
     * @param CoinCollection
     * @return CoinCollection
     */
    public function merge(CoinCollection $other)
    {
        return new CoinCollection(array_merge($this->coins, $other->coins));
    }

    /**
     * push any type of coin
     *
     * @return void
     */
    public function push(Coin $coin)
    {
        $this->coins[] = $coin;
    }

    /**
     * Calculate the value of a coin collection
     *
     * @return int
     */
    public function value()
    {
        return array_reduce($this->coins, function ($acc, $coin) {
            return $coin->value() + $acc;
        });
    }

    /**
     * Sort a coin collection by value descending
     * Modifies collection
     *
     * @return CoinCollection
     */
    public function sortByValueDesc()
    {
        // sort array of coins by value desc
        usort($this->coins, function ($coin1, $coin2) {
            return $coin1->value() < $coin2->value();
        });
        return $this;
    }
}
