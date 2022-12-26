<?php

namespace App\Controller;

use App\Repository\BurgerRepository;
use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;


class BurgersController extends AbstractController
{
    #[Route('/burgers', name: 'app_burgers', methods: ['GET'])]
    public function getBurgers(BurgerRepository $burgerRepository, CategoryRepository $categoryRepository): JsonResponse
    {
        $composeResponse = [
            'categories' => $categoryRepository->findAll(),
            'burgers' => $burgerRepository->findAll()
        ];

        return $this->json($composeResponse, 200, [], ['groups' => 'burgers:read']);
    }
}
