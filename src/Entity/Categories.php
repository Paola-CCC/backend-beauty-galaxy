<?php


declare(strict_types=1);

namespace App\Entity;

class Categories
{
    private ?int $id = null;
    private ?string $name = null;  

    public function getId():int 
    {
        return $this->id;
    }

    public function getname(): ?string {

        return $this->name;
    }

    public function setname(string $name): self 
    {
        $this->name = $name;

        return $this;
    }

}

