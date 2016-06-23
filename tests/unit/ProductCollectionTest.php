<?php
namespace My;

class ProductCollectionTest extends \Codeception\Test\Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;

    protected function _before()
    {
        $this->products = new ProductCollection();
    }

    protected function _after()
    {
    }

    // tests
    public function testEmptyCollection()
    {
        $this->assertEquals(0, count($this->products->all()));
        $this->assertEquals(new ProductCollection(), $this->products);
    }
    /**
     * @expectedException Exception
     * @expectedExceptionMessage attempt to load a null Product
     */
    public function testCollectionLoadNullProductThrowsException()
    {
        $this->products->load(new Product());
        $this->expectException(Exception::class);
    }
    public function testCollectionItemGet()
    {
        $bread = new Product('bread', 125, 3);
        $cheese = new Product('cheese', 25, 2);
        $this->products->load($bread);
        $this->assertEquals(3, $this->products->get('bread')->quantity);
        $this->assertEquals(125, $this->products->get('bread')->price);
        $this->assertEquals($bread, $this->products->get('bread'));
        $this->products->load($cheese);
        $this->assertEquals(2, $this->products->get('cheese')->quantity);
        $this->assertEquals(25, $this->products->get('cheese')->price);
        $this->assertEquals($cheese, $this->products->get('cheese'));
        $this->assertTrue($this->products->get('milk')->isNull());
    }
    public function testCollectionAdjustQuantity()
    {
        $bread = new Product('bread', 125, 3);
        $cheese = new Product('cheese', 25, 2);
        $this->products->load($bread);
        $this->products->load($cheese);
        $this->assertEquals(3, $this->products->get('bread')->quantity);
        $this->assertEquals(2, $this->products->get('cheese')->quantity);
        $this->products->get('bread')->quantity -= 2;
        $this->assertEquals(1, $this->products->get('bread')->quantity);
        $this->products->get('cheese')->quantity += 2;
        $this->assertEquals(4, $this->products->get('cheese')->quantity);
    }
}
