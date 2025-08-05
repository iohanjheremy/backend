<?php

namespace App\Presenca\Application\UseCase;

use App\Presenca\Domain\Entity\Presenca;
use App\Presenca\Domain\Repository\PresencaRepositoryInterface;

class AtualizarPresencaUseCase
{
    public function __construct(private PresencaRepositoryInterface $presencaRepository)
    {}

    public function execute(int $id, bool $presente): ?Presenca
    {
        $presenca = $this->presencaRepository->findById($id);

        if(!$presenca){
            return null;
        }

        $presenca->setPresente($presente);
        $this->presencaRepository->save($presenca);

        return $presenca;
    }
}