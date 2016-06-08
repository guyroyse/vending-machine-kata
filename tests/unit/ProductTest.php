<?php
namespace My;

class ProductTest extends \Codeception\TestCase\Test
{
    /**
     * @var \UnitTester
     */
    protected $tester;

    protected function _before()
    {
    }

    protected function _after()
    {
    }

    // tests
    public function testProduct()
    {
        $cola = new Product("cola", 1.00, 3);
        $chips = new Product("chips", 0.50, 2);
        $candy = new Product("candy", 0.65, 1);
        $this->assertEquals("cola", $cola->name);
        $this->assertEquals(1.00, $cola->price);
        $this->assertEquals(3, $cola->quantity);
        $this->assertEquals("chips", $chips->name);
        $this->assertEquals(0.50, $chips->price);
        $this->assertEquals(2, $chips->quantity);
        $this->assertEquals("candy", $candy->name);
        $this->assertEquals(0.65, $candy->price);
        $this->assertEquals(1, $candy->quantity);
    }
}
