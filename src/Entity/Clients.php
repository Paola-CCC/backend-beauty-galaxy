<?php


declare(strict_types=1);

namespace App\Entity;

use DateTime;
use DateTimeImmutable;

class Clients
{
    private int $id;
    private string $username;
    private string $email;
    private ?\DateTimeImmutable $createdAt;
    private $role;
    private ?string $birthday;
    private string $firstname;
    private string $lastname;


    public function getId():int 
    {
        return $this->id;
    }

    public function setId(int $id)
    {
        $this->id = $id;

        return $this;
    }


    public function getUsername(): ?string {

        return $this->username;
    }

    public function setUsername(string $username): self 
    {
        $this->username = $username;

        return $this;
    }

    public function getEmail():string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getCreatedAt(): ?string
    {
        if ($this->createdAt instanceof \DateTimeImmutable) {
            return $this->createdAt->format('d/m/Y H:i');
        }   

        return null;
    }

    public function setCreatedAt(?\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getRole()
    {
        return $this->role;
    }

    public function setRole(string|int $role) 
    {
        $this->role = $role;

        return $this;
    }

    public function getBirthday(): ? string 
    {
        $birthdayData = new DateTime($this->birthday);
        return DateTimeImmutable::createFromMutable($birthdayData)->format('d/m/Y H:i:s');
    }

    public function setBirthday( ?string $birthday) 
    {
        $this->birthday = $birthday;

        return $this;
    }

    public function getFirstname(): ?string 
    {
        return $this->firstname;
    }

    public function setFirstname(?string $firstname): self 
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string  
    {
        return $this->lastname;
    }

    public function setLastname(?string $lastname) 
    {
        $this->lastname = $lastname;

        return $this;
    }

}

