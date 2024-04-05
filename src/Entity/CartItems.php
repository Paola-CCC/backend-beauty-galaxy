<?php

declare(strict_types=1);

namespace App\Entity;

class CartItems
{
    private ?int $id = null;
    private ?int $user_id = null;
    private ?int $totalPricing = null;
    private ?int $quantity_chosen = null;
    private ?string $createdAt = null;
    private ?string $status = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getUserId(): ?int
    {
        return $this->user_id;
    }

    public function setUserId(?int $user_id): self
    {
        $this->user_id = $user_id;

        return $this;
    }

    public function getTotalPricing(): ?int
    {
        return $this->totalPricing;
    }

    public function setTotalPricing(?int $totalPricing): self
    {
        $this->totalPricing = $totalPricing;

        return $this;
    }

    public function getQuantityChosen(): ?int
    {
        return $this->quantity_chosen;
    }

    public function setQuantityChosen(?int $quantity_chosen): self
    {
        $this->quantity_chosen = $quantity_chosen;

        return $this;
    }

    public function getCreatedAt(): ?string
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?string $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(?string $status): self
    {
        $this->status = $status;

        return $this;
    }
}
