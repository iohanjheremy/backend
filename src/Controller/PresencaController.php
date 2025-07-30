<?php

namespace App\Controller;

use OpenApi\Attributes as OA;
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

class PresencaController extends AbstractController
{

    #[OA\Post(
        summary: 'Registra a presença de um aluno e uma aula',
        requestBody: new OA\RequestBody(
            request: true,
            content: new OA\JsonContent(
                type: "object",
                required: ["aluno_id", "aula_id", "presente"],
                properties: [
                    new OA\Property(property: "aluno_id", type: "integer", example: 1),
                    new OA\Property(property: "aula_id", type: "integer", example: 3),
                    new OA\Property(property: "presente", type: "boolean", example: true),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: "Presença Registrada com sucesso"
            ),
            new OA\Response(
                response: 400,
                description: "Dados invalidos"
            )
        ]
    )]


    #[Route('api/presencas', name: 'registrar_presenca', methods: ['POST'])]

    public function registrar(
        Request $request,
        EntityManagerInterface $em,
        AlunoRepository $alunoRepo,
        AulaRepository $aulaRepo
    ): JsonResponse {

        $data = json_decode($request->getContent(), true);

        $aluno = $alunoRepo->find($data['aluno_id']);
        $aula = $aulaRepo->find($data['aula_id']);

        if (!$aluno || !$aula) {
            return $this->json(['error' => 'Aluno ou Aula não encontrada.'], 404);
        }

        $presenca = new Presenca();
        $presenca->setAluno($data['aluno_id']);
        $presenca->setAula($data['aula_id']);
        $presenca->setPresente((bool)$data['presente']);


        $em->persist($presenca);
        $em->flush();

        return $this->json([
            'mensagem' => 'Presenca registrada com sucesso',
            'presenca_id' => $presenca->getId()
        ], 201);
    }
}
