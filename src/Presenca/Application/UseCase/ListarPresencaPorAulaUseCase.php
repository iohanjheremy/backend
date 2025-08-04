<?php


namespace App\Presenca\Application\UseCase;

use App\Presenca\Domain\Repository\PresencaRepositoryInterface;

class ListarPresencaPorAulaUseCase 
{
    public function __construct(private PresencaRepositoryInterface $presencaRepository)
    {}

    public function execute (int $aulaId): array
    {
        return $this->presencaRepository->findByAulaId($aulaId);
    }
}