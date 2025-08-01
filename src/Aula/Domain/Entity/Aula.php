<?php

namespace App\Aula\Domain\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Presenca\Domain\Entity\Presenca;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

#[ORM\Entity]
#[ORM\Table(name: "aula")]

class Aula 
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private ?int $id = null;

    #[ORM\Column(type: "date")]
    private \DateTimeInterface $data;

    #[ORM\OneToMany(mappedBy: "aula", targetEntity: Presenca::class)]
    private Collection $presencas;

    public function __construct()
    {
        $this->presencas = new ArrayCollection();
    }

    public function getId(): ?int{
        return $this-> id;
    }

    public function getData(): \DateTimeInterface{
        return $this-> data;
    }

    public function setData(\DateTimeInterface $data): self{
        $this-> data = $data;
        return $this;
    }

    /**
     * @return Collection<int, Presenca>
     */
    public function getPresencas(): Collection
    {
        return $this->presencas;
    }

}