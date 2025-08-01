<?php

namespace App\Aula\Application\UseCase;

use App\Aula\Domain\Entity\Aula;
use App\Aula\Domain\Repository\AulaRepositoryInterface;

class CriarAula
{
    public function __construct(private AulaRepositoryInterface $aulaRepo){}

    public function execute (string $data): Aula
    {
        $aula = new Aula();
        $aula->setData(new \DateTime($data));
        $this->aulaRepo->save($aula);

        return $aula;
    }
}