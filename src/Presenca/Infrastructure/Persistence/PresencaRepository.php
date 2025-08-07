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

    public function findByAulaId(int $aulaId): array
    {
        return $this->createQueryBuilder('p')
            ->join('p.aluno', 'a')
            ->addSelect('a')
            ->where('p.aula = :aulaId')
            ->setParameter('aulaId', $aulaId)
            ->getQuery()
            ->getResult();
    }

     public function findByAlunoId(int $alunoId): array
    {
        
        return $this->createQueryBuilder('p')
                ->where('p.aluno = :alunoId')
                ->setParameter('alunoId', $alunoId)
                ->getQuery()
                ->getResult();
    }


    public function findById(int $id): ?Presenca
    {
        return $this->find($id);
    }

    public function findAll():array
    {
        return $this->em->getRepository(Presenca::class)->findAll();
    }

    public function remove (Presenca $presenca): void
    {
        $this->em->remove($presenca);
        $this->em->flush();
    }
}