<?php 

declare(strict_types=1);

namespace App\Entity;

class Reviews 

{
    private ?int $id;
    private ?int $clientId;
    private ?int $productId;
    private ?int $rating;
    private ?string $comment;
    private ?\DateTimeImmutable $createdAt;
    private ?\DateTimeImmutable $updatedAt;


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

    public function getRating(): ?int {
        return $this->rating;
    }

    public function setRating(?int $rating): void {
        $this->rating = $rating;
    }

    public function getComment(): ?string {
        return $this->comment;
    }

    public function setComment(?string $comment): void {
        $this->comment = $comment;
    }

    public function getCreatedAt(): ?string {
        return $this->createdAt;
    }

    public function setCreatedAt(?string $createdAt): void {
        $this->createdAt = $createdAt;
    }

    public function getUpdatedAt(): ?string {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?string $updatedAt): void {
        $this->updatedAt = $updatedAt;
    }
}
