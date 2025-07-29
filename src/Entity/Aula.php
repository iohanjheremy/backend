<?php

namespace App\Entity;

use App\Repository\AulaRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AulaRepository::class)]

class Aula {
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    
    private int $id;

    #[ORM\Column(type: "date")]
    private \DateTimeInterface $data;

    public function getId(): ?int{
        return $this-> id;
    }

    public function getData(): \DateTimeINterface{
        return $this-> data;
    }

    public function setData(\DateTimeInterface $data): self{
        $this-> data = $data;
        return $this;
    }

}