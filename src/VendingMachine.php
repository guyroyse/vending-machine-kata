<?php
namespace My;

class VendingMachine
{
    public $currentAmount = array();
    public $coinReturn = array();
    public function accept($coin)
    {
        $cv = $coin->value();
        if ($cv == -1)
            $this->coinReturn[] = $coin;
        else
            $this->currentAmount[] = $coin;
    }
    public function display()
    {
        $tot = 0;
        //echo 'currentAmount:'.var_export($this->currentAmount,true)."\n";
        //echo 'coinReturn:'.var_export($this->coinReturn,true)."\n";
        if (count($this->currentAmount) == 0)
            return "INSERT COIN";
        foreach ($this->currentAmount as $coin) {
            $tot += $coin->value();
        }
        return sprintf("$%0.2f", $tot / 100);
    }
}
