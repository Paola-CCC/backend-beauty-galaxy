<?php


declare(strict_types=1);

namespace App\Entity;

class SubCategories
{
    private ?int $id = null;
    private ?string $name = null;  
    private ?int $categoryId = null;  

    
    public function getId():int 
    {
        return $this->id;
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

    public function getCategoryId(): ?string 
    {

        return $this->categoryId;
    }

    public function setCategoryId(string $categoryId): self 
    {
        $this->categoryId = $categoryId;

        return $this;
    }

}

