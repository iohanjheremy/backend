<?php

namespace App\Presenca\Application\UseCase;

use App\Presenca\Domain\Entity\Presenca;
use App\Presenca\Domain\Repository\PresencaRepositoryInterface;

class RegistrarPresencaUseCase
{
    public function __construct(private PresencaRepositoryInterface $repository){}

    public function execute(Presenca $presenca): void
    {
        $this->repository->save($presenca);
    }
}