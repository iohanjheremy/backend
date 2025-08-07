<?php


namespace App\Presenca\Application\UseCase;

use App\Aula\Domain\Repository\AulaRepositoryInterface;
use App\Presenca\Domain\Entity\Presenca;
use App\Presenca\Domain\Repository\PresencaRepositoryInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ListarPresencaUseCase 
{
    private PresencaRepositoryInterface $presencaRepository;

    public function __construct(
        PresencaRepositoryInterface $presencaRepository,
    ) {
        $this->presencaRepository = $presencaRepository;
    }

    public function execute (): array
    {
        return $this->presencaRepository->findAll();
    }
}