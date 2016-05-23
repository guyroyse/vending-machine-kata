<?php
namespace My;

class Coin
{
    // tuple(weight, diameter, value)
    // using arbitrary numbers for weight and diameter
    const NICKLE=array(5,5,5);
    const DIME=array(10,10,10);
    const QUARTER=array(25,25,25);

    private $weight;
    private $diameter;

    public function __construct($weight=0, $diameter=0)
    {
        $this->weight = $weight;
        $this->diameter = $diameter;
    }

    public function value()
    {
        foreach (array($this::NICKLE, $this::DIME, $this::QUARTER) as $coin) {
            if ($this->weight == $coin[0] && $this->diameter == $coin[1])
                return $coin[2];
        }
        return -1;
    }
}
