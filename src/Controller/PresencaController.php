<?php

namespace App\Controller;

use App\Entity\Presenca;
use App\Entity\Aluno;
use App\Entity\Aula;
use App\Repository\AlunoRepository;
use App\Repository\AulaRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PresencaController extends AbstractController {
    #[Route('api/presencas', name: 'registrar_presenca', methods: ['POST'])]
    public function registrar (
        Request $request,
        EntityManagerInterface $em,
        AlunoRepository $alunoRepo,
        AulaRepository $aulaRepo
    ): JsonResponse {
        $data = json_decode($request->getContent(), true);

        if (!isset($data['aluno_id'], $data['aula_id'], $data['presente'])) {
            return $this->json(['erro' => 'Parâmetros obrigatórios: alunos_id, aula_id, presente (boolean)'], 400);
        }

        $presenca = new Presenca();
        $presenca->setAluno($aluno);
        $presenca->setAula($aula);
        $presenca->setPresente((bool)$data['presente']);

        $em->persist($presenca);
        $em->flush();

        return $this->json([
            'mensagem' => 'Presenca registrada com sucesso',
            'presenca_id' => $presenca->getId()
        ], 201);
    }
}