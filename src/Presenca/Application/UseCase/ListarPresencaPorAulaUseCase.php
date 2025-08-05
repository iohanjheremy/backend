<?php


namespace App\Presenca\Application\UseCase;

use App\Aula\Domain\Repository\AulaRepositoryInterface;
use App\Presenca\Domain\Repository\PresencaRepositoryInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ListarPresencaPorAulaUseCase 
{
    private PresencaRepositoryInterface $presencaRepository;
    private AulaRepositoryInterface $aulaRepository;

    public function __construct(
        PresencaRepositoryInterface $presencaRepository,
        AulaRepositoryInterface $aulaRepository
    ) {
        $this->presencaRepository = $presencaRepository;
        $this->aulaRepository = $aulaRepository;
    }

    public function execute (int $aulaId): array
    {

        $aula = $this->aulaRepository->find($aulaId);

        if (!$aula) {
            throw new NotFoundHttpException('Aula não encontrada');
        }

        return $this->presencaRepository->findByAulaId($aulaId);
    }
}