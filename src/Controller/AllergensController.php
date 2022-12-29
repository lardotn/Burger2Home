<?php

namespace App\Controller;

use App\Entity\Allergen;
use App\Repository\AllergenRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class AllergensController extends AbstractController
{
    #[Route('/allergens', name: 'app_allergens')]
    public function getAllergens(AllergenRepository $allergenRepository): JsonResponse
    {
        return $this->json($allergenRepository->findAll(), 200, [], ['groups' => 'allergens:read']);
    }

    #[Route('/allergens/{slug}', name: 'app_allergen_slug')]
    public function getAllergenBySlug(Allergen $allergen): JsonResponse
    {
        return $this->json($allergen, 200, [], ['groups' => 'allergensDetail:read']);
    }

    #[Route('/allergens/{slug}/ingredients', name: 'app_allergen_ingredients')]
    public function getAllergenIngredients(Allergen $allergen): JsonResponse
    {
        $ingredients = array();

        foreach ($allergen->getIngredients() as $elm) {
            $ingredients[] = $elm;
        }

        return $this->json($ingredients, 200, [], ['groups' => 'allergensDetail:read']);
    }
}
