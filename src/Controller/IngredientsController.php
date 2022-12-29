<?php

namespace App\Controller;

use App\Entity\Ingredient;
use App\Repository\IngredientRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class IngredientsController extends AbstractController
{
    #[Route('/ingredients', name: 'app_ingredients')]
    public function getAllergens(IngredientRepository $ingredientRepository): JsonResponse
    {
        return $this->json($ingredientRepository->findAll(), 200, [], ['groups' => 'ingredients:read']);
    }

    #[Route('/ingredients/{slug}', name: 'app_ingredient_slug')]
    public function getAllergenBySlug(Ingredient $ingredient): JsonResponse
    {
        return $this->json($ingredient, 200, [], ['groups' => 'ingredientDetail:read']);
    }

    #[Route('/ingredients/{slug}/allergens', name: 'app_ingredient_allergens')]
    public function getAllergenIngredients(Ingredient $ingredient): JsonResponse
    {
        $ingredients = array();

        foreach ($ingredient->getAllergens() as $elm) {
            $ingredients[] = $elm;
        }

        return $this->json($ingredients, 200);
    }

    #[Route('/ingredients/{slug}/burgers', name: 'app_ingredient_burgers')]
    public function getAllergenBurgers(Ingredient $ingredient): JsonResponse
    {
        $burgers = array();

        foreach ($ingredient->getBurgers() as $elm) {
            $burgers[] = $elm;
        }

        return $this->json($burgers, 200, [], ['groups' => ['ingredients:read', 'burgerDetail:read']]);
    }
}
