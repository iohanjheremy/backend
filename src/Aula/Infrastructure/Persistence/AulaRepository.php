<?php

namespace App\Aula\Infrastructure\Persistence;

use App\Aula\Domain\Entity\Aula;
use App\Aula\Domain\Repository\AulaRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;

class AulaRepository implements AulaRepositoryInterface
{
    public function __construct(private EntityManagerInterface $em) {}

    public function save(Aula $aula): void
    {
        $this->em->persist($aula);
        $this->em->flush();
    }

    public function findAll(): array
    {
        return $this->em->getRepository(Aula::class)->findAll();
    }

    public function find(int $id): ?Aula
    {
        return $this->em->getRepository(Aula::class)->find($id);
    }
}
