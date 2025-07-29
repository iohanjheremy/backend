<?php

namespace App\Entity;

use App\Repository\AlunoRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AlunoRepository::class)]
class Aluno
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    private ?

    public function getId(): ?int
    {
        return $this->id;
    }
}
