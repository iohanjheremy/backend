<?php

namespace App\Aluno\Application\UseCase;

use App\Aluno\Domain\Entity\Aluno;
use App\Aluno\Domain\Repository\AlunoRepositoryInterface;

class CadastrarAluno 
{
    public function __construct(private AlunoRepositoryInterface $repository){}

    public function execute (string $nome): Aluno
    {
        $aluno = new Aluno($nome);
        $this->repository->save($aluno);
        return $aluno;
    }
}