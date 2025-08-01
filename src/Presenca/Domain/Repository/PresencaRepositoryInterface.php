<?php

namespace App\Presenca\Domain\Repository;

use App\Presenca\Domain\Entity\Presenca;

interface PresencaRepositoryInterface
{
    public function save(Presenca $presenca): void;
    public function findByAlunoId_AulaId(int $alunoId, int $aulaId): ?Presenca;
}