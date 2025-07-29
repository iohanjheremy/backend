<?php

namespace App\Controller;

use App\Entity\Aula;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class AulaController extends AbstractController {
    #[Route('api/aulas', name: 'cadastrar_aula', methods: ['POST'])]
    public function cadastrar(Request $request, EntityManagerInterface $em): JsonResponse {
        $data = json_decode($request->getContent(), true);

        if (!isset($data['data'])){
            return $this->json(['erro' => 'Campo "data" é obrigatório'], 400);
        }

        try{
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