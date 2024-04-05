<?php

declare(strict_types=1);

namespace App\Entity;

use DateTimeImmutable;

class Notes {

    private ?int $id = null;
    private ?int $clientId = null;
    private ?int $productId = null;
    private ?\DateTimeImmutable $createdAt = null;


    public function getId(): ?int {
        return $this->id;
    }

    public function setId(?int $id): void {
        $this->id = $id;
    }

    public function getClientId(): ?int {
        return $this->clientId;
    }

    public function setClientId(?int $clientId): void {
        $this->clientId = $clientId;
    }

    public function getProductId(): ?int {
        return $this->productId;
    }

    public function setProductId(?int $productId): void {
        $this->productId = $productId;
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

}