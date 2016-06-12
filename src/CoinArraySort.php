<?php
namespace My;

/**
 * sort an array of Coin objects
 */
class CoinArraySort
{
    /**
     * sort an array of coins by value descending
     *
     * @param array of coins
     * @return array of coins
     */
    public static function sortCoinsByValueDesc($coins = array())
    {
        // sort array of coins by value desc
        usort($coins, function ($coin1, $coin2) {
            return $coin1->value() < $coin2->value();
        });
        return $coins;
    }
}
