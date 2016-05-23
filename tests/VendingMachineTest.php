<?php
namespace My;

class VendingMachineTest extends \PHPUnit_Framework_TestCase
{
    private $vm;
    public function setUp()
    {
        $this->vm = new VendingMachine();
    }
    public function test1()
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
}
