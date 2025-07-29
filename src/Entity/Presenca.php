<?php

namespace App\Entity;

use App\Repository\PresencaRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PresencaRepository::class)]
class Presenca
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Aluno::class)]
    private ?Aluno $aluno = null;

    #[ORM\ManyToOne(targetEntity: Aula::class)]
    private ?Aula $aula = null;

    #[ORM\Column(type: "boolean")]
    private bool $presente = false;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAluno(): ?Aluno
    {
        return $this->aluno;
    }

    public function setAluno(Aluno $aluno): self{
        $this->aluno = $aluno;

        return $this;
    }

    public function getAula(): ?Aula {
        return $this-> aula;
    }

    public function setAula(Aula $aula): self{
        $this->aula = $aula;
        
        return $this;
    }

    public function isPresente(): bool{
        return $this->presente;
    }

    public function setPresente(bool $presente): self{
        $this->presente;

        return $this;
    }
}
