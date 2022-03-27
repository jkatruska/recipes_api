<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/recipe')]
class RecipeController extends AbstractController
{
    #[Route('/{id}', name: 'recipe_getById', methods: ['GET'])]
    public function getById(int $id): JsonResponse
    {
        return $this->json($this->getUser()->getUserIdentifier());
    }
}
