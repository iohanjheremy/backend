<?php

namespace App\Aula\Infrastructure\Controller;

use OpenApi\Attributes as OA;
use App\Aula\Application\UseCase\CriarAula;
use App\Aula\Domain\Repository\AulaRepositoryInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[OA\Tag(name: "Aulas")]
class AulaController extends AbstractController
{
    #[Route('api/aulas', name: 'cadastrar_aula', methods: ['POST'])]
    #[OA\Post(
        summary: "Registrar uma aula",
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                type: "object",
                required: ["data"],
                properties: [
                    new OA\Property(property: "data", type: "integer", example: "2025-07-29"),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: "Aula Registrada com sucesso"
            ),
            new OA\Response(
                response: 400,
                description: "Dados invalidos"
            )
        ]
    )]

    #[Route('/api/aulas', name: 'listar_aulas', methods: ['GET'])]
    #[OA\Get(
        summary: "Listar aulas",
        responses: [
            new OA\Response(
                response: 200,
                description: "Lista de aulas",
                content: new OA\JsonContent(
                    type: "array",
                    items: new OA\Items(
                        type: "object",
                        properties: [
                            new OA\Property(property: "id", type: "integer"),
                            new OA\Property(property: "data", type: "string", format: "date"),
                        ]
                    )
                )
            )
        ]
    )]

    
    public function listar(AulaRepositoryInterface $aulaRepo): JsonResponse
    {
        $aulas = $aulaRepo->findAll();

        $dados = array_map(fn($aula) => [
            'id' => $aula->getId(),
            'data' => $aula->getData()->format('Y-m-d'),
        ], $aulas);

        return $this->json($dados);
    }

    
    public function cadastrar(Request $request, CriarAula $criarAula): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (!isset($data['data'])) {
            return $this->json(['erro' => 'Campo "data" é obrigatório'], 400);
        }

        try {
            new \DateTime($data['data']);
        } catch (\Exception $e) {
            return $this->json(['erro' => 'Data inválida. Use o formato YYYY-MM-DD'], 400);
        }

        $aula = $criarAula->execute($data['data']);

        return $this->json([
            'menssagem' => 'Aula cadastrada com sucesso!',
            'id' => $aula->getId(),
            'data' => $aula->getData()->format('Y-m-d'),
        ]);
    }
}
