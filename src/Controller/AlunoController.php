<?php

namespace App\Controller;

use App\Repository\AlunoRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class AlunoController extends AbstractController
{
    #[Route('/api/alunos', name: 'listar_alunos', methods: ['GET'])]
    public function listar(AlunoRepository $alunoRepository): JsonResponse
    {
        $alunos = $alunoRepository->findAll();

        $dados = array_map(function ($aluno) {
            return [
                'id' => $aluno->getId(),
                'nome' => $aluno->getNome(),
            ];
        }, $alunos);

        return $this->json($dados);
    }
}
