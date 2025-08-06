<?php

namespace App\Aluno\Infrastructure\Controller;

use OpenApi\Attributes as OA;
use App\Aluno\Domain\Entity\Aluno;
use App\Aluno\Domain\Repository\AlunoRepositoryInterface;
use App\Aluno\Application\UseCase\ResumoPresencasAlunoUseCase;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[OA\Tag(name: "Alunos")]
class AlunoController extends AbstractController
{
    #[Route('/api/alunos', name: 'listar_alunos', methods: ['GET'])]
    #[OA\Get(
        summary: 'Listar todos os alunos cadastrados',
        responses: [
            new OA\Response(
                response: 200,
                description: 'Listar alunos',
                content: new OA\JsonContent(
                    type: 'array',
                    items: new OA\Items(
                        type: 'object',
                        properties: [
                            new OA\Property(property: 'id', type: 'integer', example: 1),
                            new OA\Property(property: 'nome', type: 'string', example: 'Joao da Silva'),
                        ]
                    )
                )
            )
        ]
    )]

    public function listar(AlunoRepositoryInterface $alunoRepository): JsonResponse
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


    #[Route('/api/alunos/presencas/{id}/resumo', name: 'resumo_presencas_aluno', methods: ['GET'])]
    #[OA\Get(
        summary: 'Resumo de presença e ausência do aluno',
        responses: [
            new OA\Response(
                response: 200,
                description: 'Resumo das presenças',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'presencas', type: 'integer', example: 5),
                        new OA\Property(property: 'ausencias', type: 'integer', example: 3)
                    ]
                )
            ),
            new OA\Response(
                response: 404,
                description: 'Aluno não encontrado'
            )
        ]
    )]

    public function resumoPorAluno(
        int $id,
        AlunoRepositoryInterface $alunoRepository,
        ResumoPresencasAlunoUseCase $useCase
    ): JsonResponse {
        $aluno = $alunoRepository->find($id);

        if (!$aluno) {
            return $this->json(['erro' => 'Aluno não encontrado'], 404);
        }

        $resumo = $useCase->execute($id);

        return $this->json($resumo);
    }



    #[Route('/api/alunos', name: 'cadastrar_aluno', methods: ['POST'])]
    #[OA\Post(
        summary: 'Cadastrar um novo aluno',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['nome'],
                properties: [
                    new OA\Property(property: 'nome',  type: 'string', example: 'Joao da Silva')
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: 'Aluno Cadastrado com sucesso'
            ),
            new OA\Response(
                response: 400,
                description: "Requisição inválida"
            )
        ]
    )]


    public function cadastrar(Request $request,  EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (!isset($data['nome']) || empty($data['nome'])) {
            return $this->json(['erro' => 'O campo "nome" é obrigatório'], 400);
        }

        $aluno = new Aluno($data['nome']);
        $aluno->setNome($data['nome']);

        $em->persist($aluno);
        $em->flush();

        return $this->json([
            'mensagem' => 'Aluno cadastrado com sucesso',
            'aluno_id' => $aluno->getId(),
            'nome' => $aluno->getNome()
        ], 201);
    }
}
