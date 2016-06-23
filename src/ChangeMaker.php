<?php
namespace My;

// \Codeception\Util\Debug::debug("message");

/**
 * class ChangeMaker is responsible to make change if possible after an item
 * is selected.
 *
 * Change can be constructed by using coins from the coinBox and coinCurrent arrays.
 *
 * makeChange() tries to use the largest denomination coins first.
 * WHen a given denomination is too large or runs out, the next largest denomination is used.
 *
 * makeChange() returns null when change cannot bee made from the available coins.
 * Otherwise a tuple of change_to_keep and change_to_return will be returned to the caller.
 */
class ChangeMaker
{
    /**
     * make change if possible
     *
     * return array(coinsToKeep, coinsToReturn) if able to make change
     * return null if unable to make change
     *
     * @param integer $price
     * @param CoinCollection $coinCurrent
     * @param CoinCollection $coinBox
     *
     * @return array
     */
    public static function makeChange($price, CoinCollection $coinCurrent, CoinCollection $coinBox)
    {
        $allCoins = $coinCurrent->merge($coinBox);
        $coinsToReturn = new CoinCollection();
        $coinsToKeep = new CoinCollection();

        $valueOfChangeAvail = 0;
        $valueOfChangeNeeded = $coinCurrent->value() - $price;
        // partition all the coins into coins to keep and coins to return
        foreach ($allCoins->sortByValueDesc()->all() as $coin) {
            if ($valueOfChangeAvail + $coin->value() > $valueOfChangeNeeded) {
                $coinsToKeep->push($coin);
                continue;
            }
            $valueOfChangeAvail += $coin->value();
            $coinsToReturn->push($coin);
        }
        if ($valueOfChangeAvail != $valueOfChangeNeeded) {
            return array();
        }
        return array('received' => $coinsToKeep, 'change' => $coinsToReturn);
    }
}
