<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProductRepository::class)]
/**
 * @ORM\Entity(repositoryClass=App\Repository\ProductRepository::class)
 */
class Product
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    // @phpstan-ignore-next-line
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column]
    private ?int $value = null;

    /**
     * getId
     *
     * @return int
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * getName
     *
     * @return string
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * setName
     *
     * @param  string $name
     * @return static
     */
    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    /**
     * getValue
     *
     * @return int
     */
    public function getValue(): ?int
    {
        return $this->value;
    }

    /**
     * setValue
     *
     * @param  int $value
     * @return static
     */
    public function setValue(int $value): static
    {
        $this->value = $value;

        return $this;
    }
}
