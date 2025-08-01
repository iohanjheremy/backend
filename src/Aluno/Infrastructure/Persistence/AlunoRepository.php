<?php

namespace App\Aluno\Infrastructure\Persistence;

use App\Aluno\Domain\Entity\Aluno;
use App\Aluno\Domain\Repository\AlunoRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;

class AlunoRepository implements AlunoRepositoryInterface
{
    public function __construct(private EntityManagerInterface $em){}

    public function findAll(): array
    {
        return $this->em->getRepository(Aluno::class)->findAll();

    }

    public function find(int $id): ?Aluno
    {
        return $this->em->getRepository(Aluno::class)->find($id);
    }

    public function save(Aluno $aluno): void{
        $this->em->persist($aluno);
        $this->em->flush();
    }
}