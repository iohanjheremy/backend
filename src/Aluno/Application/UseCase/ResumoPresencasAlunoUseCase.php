<?php

namespace App\Aluno\Application\UseCase;

use App\Presenca\Domain\Repository\PresencaRepositoryInterface;

class ResumoPresencasAlunoUseCase
{
    public function __construct(private PresencaRepositoryInterface $presencaRepository){}

    public function execute(int $alunoId): array
    {
        $todas = $this->presencaRepository->findByAlunoId($alunoId);
        
        $presentes = 0;
        $ausentes = 0;

        foreach ($todas as $presenca) {
            if ($presenca->isPresente()){
                $presentes++;
            } else{
                $ausentes++;
            }
        }

        return [
            'aluno_id' => $alunoId,
            'presenças' => $presentes,
            'ausencias' => $ausentes,
            'total' => $presentes + $ausentes,
        ];
    }
}