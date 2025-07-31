<?php

namespace App\Tests\Repository;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ProductRepository;
use App\Entity\Product;

/**
 * Test cases for class ProductRepository.
 */
class ProductRepositoryTest extends KernelTestCase
{
    private EntityManagerInterface $entityManager;
    private ProductRepository $repository;

    /**
     * setUp
     *
     * @return void
     */
    protected function setUp(): void
    {
        self::bootKernel();

        $kernel = self::$kernel;

        // Check if kernel is not null
        if ($kernel === null) {
            throw new \RuntimeException('Kernel is not initialized.');
        }

        // Access the container
        $container = $kernel->getContainer();

        // Get the Doctrine registry service
        $doctrine = $container->get('doctrine');

        // Get the EntityManager from the container
        // @phpstan-ignore-next-line
        $this->entityManager = $doctrine->getManager();

        // Clear existing data
        $this->entityManager->getConnection()->executeStatement('DELETE FROM product');

        // Initialize the repository
        // @phpstan-ignore-next-line
        $this->repository = $this->entityManager->getRepository(Product::class);

        // Insert test data
        $products = [
            new Product(),
            new Product(),
            new Product(),
        ];

        $products[0]->setName('Product 1');
        $products[0]->setValue(10);
        $products[1]->setName('Product 2');
        $products[1]->setValue(20);
        $products[2]->setName('Product 3');
        $products[2]->setValue(30);

        foreach ($products as $product) {
            $this->entityManager->persist($product);
        }
        $this->entityManager->flush();
    }

    /**
     * testFindByMinimumValue
     *
     * @return void
     */
    public function testFindByMinimumValue(): void
    {
        // Find products with value >= 15
        $results = $this->repository->findByMinimumValue(15);

        // Assert that only products with value >= 15 are returned
        $this->assertCount(2, $results);
        $this->assertEquals(20, $results[0]->getValue());
        $this->assertEquals(30, $results[1]->getValue());
    }

    /**
     * testFindByMinimumValue2
     *
     * @return void
     */
    public function testFindByMinimumValue2(): void
    {
        // Find products with value >= 15 using raw SQL
        $results = $this->repository->findByMinimumValue2(15);

        // Assert array results
        //$this->assertIsArray($results);
        $this->assertCount(2, $results);
        $this->assertEquals(20, $results[0]['value']);
        $this->assertEquals(30, $results[1]['value']);
    }
}
