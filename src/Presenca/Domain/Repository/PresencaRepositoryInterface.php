<?php

namespace App\Presenca\Domain\Repository;

use App\Presenca\Domain\Entity\Presenca;

interface PresencaRepositoryInterface
{
    public function save(Presenca $presenca): void;
    public function findByAlunoId_AulaId(int $alunoId, int $aulaId): ?Presenca;
    public function findByAulaId(int $aulaId): array;
    public function findByAlunoId(int $alunoId): array;
    public function findById(int $id): ?Presenca;
    public function remove (Presenca $presenca): void;
}