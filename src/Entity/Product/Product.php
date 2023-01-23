<?php

namespace App\Entity\Product;

use App\Handlers\ModelHandler;
use App\Repository\Product\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping as ORM;
use Exception;

#[ORM\Entity(repositoryClass: ProductRepository::class)]
class Product
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column]
    private ?float $price = null;

    #[ORM\ManyToOne(inversedBy: 'products')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Model $model = null;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return $this
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return float|null
     */
    public function getPrice(): ?float
    {
        return $this->price;
    }

    /**
     * @param float $price
     * @return $this
     */
    public function setPrice(float $price): self
    {
        $this->price = $price;

        return $this;
    }

    /**
     * @return Model|null
     */
    public function getModel(): ?Model
    {
        return $this->model;
    }

    /**
     * @param Model|null $model
     * @return $this
     */
    public function setModel(?Model $model): self
    {
        $this->model = $model;

        return $this;
    }

    /**
     * @param ModelHandler $handler
     * @param EntityManagerInterface $em
     * @return void
     * @throws Exception
     */
    #[ORM\PrePersist, ORM\PreUpdate]
    public function assertUniqueProductName(ModelHandler $handler, EntityManagerInterface $em): void
    {
        $model = $handler->getModelByModelId($this->getModel()->getId(), $em);

        foreach ($model->getProducts() as $product)
        {
            if ($product->getName() === $this->name)
            {
                throw
                new Exception('Product name must have a unique Name within the current Model');
            }
        }
    }
}
