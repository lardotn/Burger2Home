<?php

namespace App\Controller;

use App\Entity\Burger;
use App\Entity\Category;
use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class CategoriesController extends AbstractController
{
    #[Route('/categories', name: 'app_categories')]
    public function getAllergens(CategoryRepository $categoryRepository): JsonResponse
    {
        return $this->json($categoryRepository->findAll(), 200, [], ['groups' => 'categories:read']);
    }

    #[Route('/categories/{slug}', name: 'app_category_slug')]
    public function getAllergenBySlug(Category $category): JsonResponse
    {
        return $this->json($category, 200, [], ['groups' => 'categoryDetail:read']);
    }

    #[Route('/categories/{slug}/burgers', name: 'app_category_burgers')]
    public function getAllergenIngredients(Category $category): JsonResponse
    {
        $burgers = array();

        foreach ($category->getBurgers() as $elm) {
            $burgers[] = $elm;
        }

        return $this->json($burgers, 200, [], ['groups' => ['categories:read', 'burgerDetail:read']]);
    }
}
