<?php

namespace App\Presenca\Infrastructure\Controller;

use OpenApi\Attributes as OA;
use App\Aula\Domain\Repository\AulaRepositoryInterface;
use App\Aluno\Domain\Repository\AlunoRepositoryInterface;
use App\Presenca\Application\UseCase\ListarPresencaPorAulaUseCase;
use App\Presenca\Application\UseCase\RegistrarPresencaUseCase;
use App\Presenca\Domain\Entity\Presenca;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[OA\Tag(name: 'Presencas')]
class PresencaController extends AbstractController
{

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

    #[Route('/api/presencas', name: 'registrar_presenca', methods: ['POST'])]
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


    #[Route('/api/presencas/aulas/{id}', name: 'listar_presenca_por_aula', methods: ['GET'])]
    public function listarPorAula(int $id, ListarPresencaPorAulaUseCase $useCase)
    {
        $presencas = $useCase->execute($id);

        $dados = array_map(fn($presenca) => [
            'aluno_id' => $presenca->getALuno()->getId(),
            'aluno_nome' => $presenca->getAluno()->getNome(),
            'presente' => $presenca->isPresente(),
        ], $presencas);

        return $this->json($dados);
    }
}
