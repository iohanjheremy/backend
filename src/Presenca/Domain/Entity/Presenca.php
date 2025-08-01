<?php

namespace App\Presenca\Domain\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Aluno\Domain\Entity\Aluno;
use App\Aula\Domain\Entity\Aula;

#[ORM\Entity]
#[ORM\Table(name: "presenca")]
class Presenca 
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Aluno::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?Aluno $aluno = null;

    #[ORM\ManyToOne(inversedBy: "presencas")]
    #[ORM\JoinColumn(nullable: false)]
    private ?Aula $aula = null;

    #[ORM\Column]
    private bool $presente;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAluno(): ?Aluno
    {
        return $this->aluno;
    }

    public function setAluno(Aluno $aluno): self
    {
        $this->aluno = $aluno;
        return $this;
    }

    public function getAula(): ?Aula
    {
        return $this->aula;
    }

    public function setAula(Aula $aula): self
    {
        $this->aula = $aula;
        return $this;
    }

    public function isPresent(): bool 
    {
        return $this->presente;
    }

    public function setPresente(bool $presente): self
    {
        $this->presente = $presente;
        return $this;
    }
}