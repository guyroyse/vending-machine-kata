<?php
namespace My;

class VendingMachineTest extends \PHPUnit_Framework_TestCase
{
    private $vm;
    public function setUp()
    {
        $this->vm = new VendingMachine();
    }

    public function testAcceptCoins()
    {
        $this->vm->accept(new Coin());
        $this->assertEquals(1, count($this->vm->coinReturn));
        $this->assertEquals("INSERT COIN", $this->vm->display());
        $this->assertEquals(new Coin(), $this->vm->coinReturn[0]);

        $this->vm->accept(new Coin(5, 0));
        $this->assertEquals(2, count($this->vm->coinReturn));
        $this->assertEquals("INSERT COIN", $this->vm->display());
        $this->assertEquals(new Coin(5, 0), $this->vm->coinReturn[1]);

        $this->vm->accept(new Coin(3, 3));
        $this->assertEquals(3, count($this->vm->coinReturn));
        $this->assertEquals("INSERT COIN", $this->vm->display());
        $this->assertEquals(new Coin(3, 3), $this->vm->coinReturn[2]);

        $this->vm->accept(new Coin(5, 5));
        $this->assertEquals(3, count($this->vm->coinReturn));
        $this->assertEquals("$0.05", $this->vm->display());

        $this->vm->accept(new Coin(10, 10));
        $this->assertEquals(3, count($this->vm->coinReturn));
        $this->assertEquals("$0.15", $this->vm->display());
    }

    public function testSelectProduct()
    {
        $this->vm->accept(new Coin(25, 25));
        $this->vm->accept(new Coin(25, 25));
        $this->vm->accept(new Coin(25, 25));
        $this->vm->load(new Product("cola", 100, 2));
        $this->vm->load(new Product("candy", 65, 3));
        $this->vm->load(new Product("steak", 999, 0));
        $this->assertEquals("$0.75", $this->vm->display());
        $this->assertEquals("PRICE $1.00", $this->vm->select("cola"));
        $this->assertEquals("OUT OF ITEM", $this->vm->select("steak"));

        $this->vm->accept(new Coin(25, 25));
        $this->assertEquals("$1.00", $this->vm->display());
        $this->assertEquals("THANK YOU", $this->vm->select("cola"));
        //$this->assertEquals("eh?", var_export($this->vm->products,true));
    }

    public function _testMakeChange()
    {
    }

    public function _testReturnCoins()
    {
    }

    public function _testSoldOut()
    {
    }

    public function _testExactChangeOnly()
    {
    }
}
