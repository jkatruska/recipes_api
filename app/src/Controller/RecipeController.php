<?php

declare(strict_types=1);

namespace App\Controller;

use App\Domain\Normalizer\Json\Entity\RecipeNormalizer;
use App\Exception\PermissionException;
use App\Exception\ServerException;
use App\Exception\ValidationException;
use App\Service\RecipeService;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

class RecipeController extends AbstractController
{
    public function __construct(
        private RecipeService $recipeService
    ) {
    }

    /**
     * @param UserInterface $user
     * @param Request $request
     * @return JsonResponse
     */
    #[Route('/recipes', name: 'recipe_getAllRecipes', methods: ['GET'])]
    public function getAllRecipes(UserInterface $user, Request $request): JsonResponse
    {
        $private = (bool) $request->query->get('private', true);
        $recipes = $this->recipeService->getRecipes($user, $private);
        return $this->json($recipes);
    }

    /**
     * @param UserInterface $user
     * @param int $id
     * @return JsonResponse
     * @throws PermissionException
     * @throws NonUniqueResultException
     */
    #[Route('/recipe/{id}', name: 'recipe_getById', methods: ['GET'])]
    public function getById(UserInterface $user, int $id): JsonResponse
    {
        try {
            $recipe = $this->recipeService->getRecipe($user, $id);
        } catch (NoResultException) {
            return $this->json(null, Response::HTTP_NOT_FOUND);
        }
        return $this->json($recipe, context: ['render' => RecipeNormalizer::CONTEXT_DETAIL]);
    }

    /**
     * @param UserInterface $user
     * @param Request $request
     * @return JsonResponse
     * @throws ValidationException|ServerException
     */
    #[Route('/recipe', name: 'recipe_addRecipe', methods: ['POST'])]
    public function addRecipe(UserInterface $user, Request $request): JsonResponse
    {
        $this->recipeService->addRecipe($user, $request->request->all());
        return $this->json(null, Response::HTTP_CREATED);
    }
}
