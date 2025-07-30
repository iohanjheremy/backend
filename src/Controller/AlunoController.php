<?php

namespace App\Controller;

use OpenApi\Attributes as OA;
use App\Entity\Aluno;
use App\Repository\AlunoRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

 #[OA\Tag(name: 'Alunos')]
class AlunoController extends AbstractController
{
    #[OA\Post(
        path: '/api/alunos/{nome}',
        summary: 'Cadastrar um novo aluno',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['nome'],
                properties: [
                    new OA\Property(property: 'nome', type: 'string', example: 'Joao da Silva')
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: 'Aluno cadastrado com sucesso'
            ),
            new OA\Response(
                response: 400,
                description: 'Requisição inválida'
            )
        ]
    )]

    #[Route('/api/alunos', name: 'cadastrar_aluno', methods: ['POST'])]
    public function cadastrar(Request $request,  EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (!isset($data['nome']) || empty($data['nome'])) {
            return $this->json(['erro' => 'O campo "nome" é obrigatório'], 400);
        }

        $aluno = new Aluno();
        $aluno->setNome($data['nome']);

        $em->persist($aluno);
        $em->flush();

        return $this->json([
            'mensagem' => 'Aluno cadastrado com sucesso',
            'aluno_id' => $aluno->getId(),
            'nome' => $aluno->getNome()
        ], 201);
    }

    #[OA\Get(
        path: '/api/alunos',
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
