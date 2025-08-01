<?php

namespace App\Aula\Domain\Repository;

use App\Aula\Domain\Entity\Aula;

interface AulaRepositoryInterface
{
    public function save(Aula $aula): void;
    public function findAll(): array;
    public function find(int $id): ?Aula;
}
