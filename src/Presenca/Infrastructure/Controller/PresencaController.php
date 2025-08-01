<?php

namespace App\Presenca\Infrastructure\Controller;

use OpenApi\Attributes as OA;
use App\Aula\Domain\Repository\AulaRepositoryInterface;
use App\Aluno\Domain\Repository\AlunoRepositoryInterface;
use App\Presenca\Application\UseCase\RegistrarPresencaUseCase;
use App\Presenca\Domain\Entity\Presenca;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[OA\Tag(name: 'Presencas')]
class PresencaController extends AbstractController
{
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

    public function __invoke(
        Request $request,
        RegistrarPresencaUseCase $useCase,
        AlunoRepositoryInterface $alunoRepo,
        AulaRepositoryInterface $aulaRepo
    ): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

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

        return $this->json(['mensagem' => 'Presença registrada com sucesso!',201]);
    }
}