<?php 

declare(strict_types=1);

namespace App\Entity;

use DateTimeImmutable;

class Products {

    private ?int $id = null;
    private ?int $brand_id = null;
    private ?string $name = null;
    private ?string $description_short = null;
    private ?string $description_long = null;
    private ?string $product_image = null;
    private ?int $quantity = null;
    private ?int $category_id = null;
    private ?\DateTimeImmutable $createdAt;
    private ?\DateTimeImmutable $updatedAt;
    private int $price;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getBrand_id(): ?int
    {
        return $this->brand_id;
    }

    public function setBrand_id(int $brand_id): self
    {
        $this->brand_id = $brand_id;

        return $this;
    }


    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription_short (): ?string
    {
        return $this->description_short;
    }

    public function setDescription_short (string $description_short): self
    {
        $this->description_short = $description_short;

        return $this;
    }

    public function getDescription_long (): ?string
    {
        return $this->description_long;
    }

    public function setDescription_long (string $description_long): self
    {
        $this->description_long = $description_long;

        return $this;
    }

    public function getProduct_image (): ?string
    {
        return $this->product_image;
    }

    public function setProduct_image (string $product_image): self
    {
        $this->product_image = $product_image;

        return $this;
    }


    public function getQuantity (): ?int
    {
        return $this->quantity;
    }

    public function setQuantity (int $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getCategory_id (): ?int
    {
        return $this->category_id;
    }

    public function setCategory_id (int $category_id): self
    {
        $this->category_id = $category_id;

        return $this;
    }

    public function getCreatedAt (): ?DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt (?DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt (): ?DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt (?DateTimeImmutable $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getPrice (): ?int
    {
        return $this->price;
    }

    public function setPrice (?int $price): self
    {
        $this->price = $price;

        return $this;
    }

}