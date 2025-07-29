<?php

namespace App\Controller;

use OpenApi\Attributes as OA;
use App\Entity\Aula;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class AulaController extends AbstractController
{


    #[OA\Post(
        path: 'api/aulas/{id}/{data}',
        summary: 'Registra uma aula',
        requestBody: new OA\RequestBody(
            request: true,
            content: new OA\JsonContent(
                type: "object",
                required: ["id", "data"],
                properties: [
                    new OA\Property(property: "id", type: "integer", example: 1),
                    new OA\Property(property: "data", type: "integer", example: 2025 - 07 - 29),
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


    #[Route('api/aulas/{id}/{data}', name: 'cadastrar_aula', methods: ['POST'])]
    public function cadastrar(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (!isset($data['data'])) {
            return $this->json(['erro' => 'Campo "data" é obrigatório'], 400);
        }

        try {
            $dataAula = new \DateTime($data['data']);
        } catch (\Exception $e) {
            return $this->json(['erro' => 'Data inválida. Use o formato YYYY-MM-DD'], 400);
        }

        $aula = new Aula();
        $aula->setData($dataAula);

        $em->persist($aula);
        $em->flush();

        return $this->json([
            'menssagem' => 'Aula cadastrada com sucesso!',
            'id' => $aula->getId(),
            'data' => $aula->getData()->format('Y-m-d'),
        ]);
    }
}
