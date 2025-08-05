<?php

namespace App\Presenca\Application\UseCase;

use App\Presenca\Domain\Repository\PresencaRepositoryInterface;

class DeletarPresencaUseCase
{
    public function __construct(private PresencaRepositoryInterface $presencaRepository)
    {}

    public function execute (int $id): bool
    {
        $presenca = $this->presencaRepository->findByID($id);

        if (!$presenca){
            return false;
        }

        $this->presencaRepository->remove($presenca);
        return true;
    }
}