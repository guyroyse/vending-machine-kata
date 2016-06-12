<?php
namespace My;

/**
 * return the total value of an array of Coin
 */
class CoinArrayValue
{
    /**
     * Calculate the value of an array of coins
     *
     * @param array of Coin
     * @return int
     */
    public static function valueOfCoins($coins = array())
    {
        return array_reduce($coins, function ($acc, $coin) {
            return $coin->value() + $acc;
        });
    }
}
