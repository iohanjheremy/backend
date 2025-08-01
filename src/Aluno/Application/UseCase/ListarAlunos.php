<?php

namespace App\Aluno\Application\UseCase;

use App\Aluno\Domain\Repository\AlunoRepositoryInterface;

class ListarAlunos {
    public function __construct(private AlunoRepositoryInterface $repository){}
        
    public function execute(): array{
        return $this->repository->findAll();
    }
}