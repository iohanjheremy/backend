<?php

namespace App\Presenca\Infrastructure\Persistence;

use App\Presenca\Domain\Entity\Presenca;
use App\Presenca\Domain\Repository\PresencaRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class PresencaRepository extends ServiceEntityRepository implements PresencaRepositoryInterface
{
    private \Doctrine\ORM\EntityManagerInterface $em;

    public function __construct(private ManagerRegistry $registry){
        parent::__construct($registry, Presenca::class);
        $this->em = $this->getEntityManager();
    }

    public function save(Presenca $presenca): void
    {   
        $this->em->persist($presenca);
        $this->em->flush();
    }

    public function findByAlunoId_AulaId(int $alunoId, int $aulaId): ?Presenca
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.aluno = :aluno')
            ->andWhere('p.aula = :aula')
            ->setParameter('aluno', $alunoId)
            ->setParameter('aula', $aulaId)
            ->getQuery()
            ->getOneOrNullResult();
    }
}