<?php

namespace App\Aluno\Domain\Repository;

use App\Aluno\Domain\Entity\Aluno;

interface AlunoRepositoryInterface {
    public function findAll(): array;
    public function find(int $id): ?Aluno;
    public function save(Aluno $aluno): void;
}