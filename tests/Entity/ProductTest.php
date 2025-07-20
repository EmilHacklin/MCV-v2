<?php

namespace App\Test\Entity;

use PHPUnit\Framework\TestCase;
use App\Entity\Product;

/**
 * Test cases for class Product.
 */
class ProductTest extends TestCase
{
    /**
     * testCreateObject
     *
     * Construct object and verify that the object has the expected
     * properties, use no arguments.
     *
     * @return void
     */
    public function testCreateObject(): void
    {
        $product = new Product();
        $this->assertInstanceOf(Product::class, $product);
    }

    /**
     * testSetAndGetMethods
     *
     * Test the set and get methods
     *
     * @return void
     */
    public function testSetAndGetMethods(): void
    {
        $product = new Product();
        $product->setName("Test");
        $product->setValue(69);

        $this->assertNull($product->getId());
        $this->assertEquals("Test", $product->getName());
        $this->assertEquals(69, $product->getValue());
    }
}
