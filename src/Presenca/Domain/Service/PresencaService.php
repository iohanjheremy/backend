<?php

namespace App\Presenca\Domain\Service;

use App\Presenca\Domain\Entity\Presenca;
use App\Aula\Domain\Entity\Aula;
use App\Aluno\Domain\Entity\Aluno;

class PresencaService
{

    public function registrarPresenca(Aluno $aluno, Aula $aula, bool $presente): Presenca
    {
        $presenca = new Presenca();
        $presenca->setAluno($aluno);
        $presenca->setAula($aula);
        $presenca->setPresente($presente);

        return $presenca;
    }
}
