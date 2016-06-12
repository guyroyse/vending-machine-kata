<?php
namespace My;

/**
 * class Coin identifes a round disk of certain weight and diameter as a valid coin
 * coins of type nickel, dime, and quarter are recognized as valid
 * any other object is a slug and has no value
 */
class Coin
{
    /*
     * ref. https://www.usmint.gov/about_the_mint/?action=coin_specifications
     *          nickel   dime     quarter
     * weight   5.000 g  2.268 g  5.670 g
     * diameter 21.21 mm 17.91 mm 24.26 mm
     */
    /*
     * coin properties: weight in grams, diameter in mm
     */
    const PROP_NICKEL    = array('weight' => 5.000, 'diameter' => 21.21);
    const PROP_DIME      = array('weight' => 2.268, 'diameter' => 17.91);
    const PROP_QUARTER   = array('weight' => 5.670, 'diameter' => 24.26);
    /*
     * coin types: property and value
     */
    const TYPE_NICKEL    = array('prop'   => self::PROP_NICKEL,  'value' => 5);
    const TYPE_DIME      = array('prop'   => self::PROP_DIME,    'value' => 10);
    const TYPE_QUARTER   = array('prop'   => self::PROP_QUARTER, 'value' => 25);
    /*
     * array of all valid coin types
     */
    const TYPE_VALIDCOIN = array(self::TYPE_NICKEL, self::TYPE_DIME, self::TYPE_QUARTER);

    private $value;
    private $weight;
    private $diameter;

    /**
     * Identify a coin by its weight and diameter
     * construct a coin object and save its properties including value
     *
     * A slug is a coin that doesn't have valid properies and is assigned a zero value
     *
     * @param array (tuple of weight and diameter)
     * @return void
     */
    public function __construct(array $prop = array('weight' => 0, 'diameter' => 0))
    {
        $this->value = 0;
        $this->weight = $prop['weight'];
        $this->diameter = $prop['diameter'];
        foreach (self::TYPE_VALIDCOIN as $typeOfCoin) {
            $prop = array('weight' => $this->weight, 'diameter' => $this->diameter);
            if ($typeOfCoin['prop'] === $prop) {
                $this->value = $typeOfCoin['value'];
            }
        }
    }

    /**
     * Return the value of a coin in cents
     *
     * @return int
     */
    public function value()
    {
        return $this->value;
    }

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
