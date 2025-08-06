<?php

namespace App\Presenca\Infrastructure\Controller;

use OpenApi\Attributes as OA;
use App\Aula\Domain\Repository\AulaRepositoryInterface;
use App\Aluno\Domain\Repository\AlunoRepositoryInterface;
use App\Presenca\Application\UseCase\AtualizarPresencaUseCase;
use App\Presenca\Application\UseCase\DeletarPresencaUseCase;
use App\Presenca\Application\UseCase\ListarPresencaPorAulaUseCase;
use App\Presenca\Application\UseCase\RegistrarPresencaUseCase;
use App\Presenca\Domain\Entity\Presenca;
use OpenApi\Annotations\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[OA\Tag(name: 'Presencas')]
class PresencaController extends AbstractController
{

    #[Route('/api/presencas/aulas/{id}', name: 'listar_presenca_por_aula', methods: ['GET'])]
    #[OA\Get(
        summary: "Listar presenças por aula",
        responses: [
            new OA\Response(
                response: 200,
                description: "Lista de Presença",
                content: new OA\JsonContent(
                    type: "array",
                    items: new OA\Items(
                        type: "object",
                        properties: [
                            new OA\Property(property: "aluno_id", type: "integer"),
                            new OA\Property(property: "aluno_nome", type: "string"),
                            new OA\Property(property: "presente", type: "boolean")
                        ]
                    )
                )
            )
        ]
    )]

    public function listarPorAula(int $id, ListarPresencaPorAulaUseCase $useCase)
    {
        $presencas = $useCase->execute($id);

        if (count($presencas) === 0) {
            return $this->json(['mensagem' => 'Nenhuma presença registrada para esta aula.'], 200);
        }

        $dados = array_map(fn($presenca) => [
            'aluno_id' => $presenca->getALuno()->getId(),
            'aluno_nome' => $presenca->getAluno()->getNome(),
            'presente' => $presenca->isPresente(),
        ], $presencas);

        return $this->json($dados);
    }




    #[Route('/api/presencas', name: 'registrar_presenca', methods: ['POST'])]
    #[OA\Post(
        summary: "Registrar Presença",
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['data'],
                properties: [
                    new OA\Property(property: 'aluno_id', type: 'integer', example: 1),
                    new OA\Property(property: 'aula_id', type: 'integer', example: 1),
                    new OA\Property(property: 'presente', type: 'boolean', example: false)
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: 'Presença Registrada com sucesso'
            ),
            new OA\Response(
                response: 400,
                description: "Requisição inválida"
            )
        ]
    )]

    public function registrar(
        Request $request,
        RegistrarPresencaUseCase $useCase,
        AlunoRepositoryInterface $alunoRepo,
        AulaRepositoryInterface $aulaRepo
    ): JsonResponse {
        $data = json_decode($request->getContent(), true);

        if (!$data || !isset($data['aluno_id'], $data['aula_id'], $data['presente'])) {
            return $this->json([
                'erro' => 'Campos obrigatórios: aluno_id, aula_id e presente são exigidos.'
            ], 400);
        }

        $aluno = $alunoRepo->find($data['aluno_id']);
        $aula = $aulaRepo->find($data['aula_id']);

        if (!$aluno || !$aula) {
            return $this->json(['erro' => 'Aluno ou Aula inválidos.'], 400);
        }

        $presenca = new Presenca();
        $presenca->setAluno($aluno)
            ->setAula($aula)
            ->setPresente((bool) $data['presente']);

        $useCase->execute($presenca);

        return $this->json(['mensagem' => 'Presença registrada com sucesso!', 201]);
    }




    #[Route('/api/presencas/{id}', name: 'atualizar_presenca', methods: ['PUT'])]
    #[OA\Put(
        summary: "Atualizar presença de um aluno",
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['presente'],
                properties: [
                    new OA\Property(property: 'presente', type: 'boolean', example: false)
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Presença atualizada com sucesso',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "id", type: "integer"),
                        new OA\Property(property: "aluno_id", type: "integer"),
                        new OA\Property(property: "aluno_nome", type: "string"),
                        new OA\Property(property: "presente", type: "boolean")
                    ]
                )
            ),
            new OA\Response(
                response: 404,
                description: 'Presença não encontrada'
            )
        ]
    )]

    public function atualizar(int $id, Request $request, AtualizarPresencaUseCase $useCase): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (!isset($data['presente'])) {
            return $this->json(['erro' => 'O campo "presente" é obrigatorio'], 400);
        }

        $resultado = $useCase->execute($id, (bool) $data['presente']);

        if (!$resultado) {
            return $this->json(['erro' => 'Presença não encontrada'], 400);
        }

        
        return $this->json([
            'mensagem' => 'Presença atualizada com sucesso',
            'presenca' => [
                'id' => $resultado->getId(),
                'aluno_id' => $resultado->getAluno()->getId(),
                'aula_id' => $resultado->getAula()->getId(),
                'presente' => $resultado->isPresente(),
            ]
        ]);
    }




    #[Route('/api/presencas/{id}', name: 'deletar_presenca', methods: ['DELETE'])]
    #[OA\Delete(
        summary: "Deletar uma presença por ID",
        parameters: [
            new OA\Parameter(
                name: 'id',
                in: 'path',
                required: true,
                description: 'ID da presença a ser deletada',
                schema: new OA\Schema(type: 'integer', example: 2)
            )
        ],
        responses: [
            new OA\Response(response: 200, description: "Presença deletada com sucesso."),
            new OA\Response(response: 404, description: "Presença não encontrada.")
        ]
    )]

    public function deletar(int $id, DeletarPresencaUseCase $useCase): JsonResponse
    {
        $confirma = $useCase->execute($id);
        if (!$confirma) {
            return $this->json(['erro' => 'Presença não encontrada', 404]);
        }

        return $this->json(['mensagem' => 'Presença deletada com sucesso']);
    }
}
