<?php

namespace App\Controller;

use App\Repository\BurgerRepository;
use App\Repository\CategoryRepository;
use App\Entity\Burger;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class BurgersController extends AbstractController
{
    #[Route('/burgers', name: 'app_burgers', methods: ['GET'])]
    public function getBurgers(BurgerRepository $burgerRepository, CategoryRepository $categoryRepository): JsonResponse
    {
        $composeResponse = array(
            'categories' => $categoryRepository->findAll(),
            'burgers' => $burgerRepository->findAll()
        );

        return $this->json($composeResponse, 200, [], ['groups' => 'burgers:read']);
    }

    #[Route('/burgers/{slug}', name: 'app_burger_slug', methods: ['GET'])]
    public function getBurgerBySlug(Burger $burger): JsonResponse
    {
        $composeResponse = array(
            'burgerDetail' => $burger,
            'categories' => $burger->getCategories(),
            'allergens' => $this->getAllergensFromBurger($burger)
        );

        return $this->json($composeResponse, 200, [], ['groups' => 'burgerDetail:read']);
    }

    #[Route('/burgers/{slug}/categories', name: 'app_burger_categories', methods: ['GET'])]
    public function getBurgerCategories(Burger $burger): JsonResponse
    {
        return $this->json($burger->getCategories(), 200, [], ['groups' => 'burgerDetail:read']);
    }

    #[Route('/burgers/{slug}/allergens', name: 'app_burger_allergens', methods: ['GET'])]
    public function getBurgerAllergens(Burger $burger): JsonResponse
    {
        return $this->json($this->getAllergensFromBurger($burger), 200, [], ['groups' => 'burgerDetail:read']);
    }

    private function getAllergensFromBurger(Burger $burger): array
    {
        $allergens = array();

        foreach ($burger->getIngredients() as $elm) 
        {
            if (count($elm->getAllergens()) > 0) {
                $allergens[] = $elm->getAllergens()[0];
            }
        }

        return $allergens;
    }
}
