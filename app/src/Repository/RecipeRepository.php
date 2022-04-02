<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Recipe;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @extends EntityRepository<Recipe>
 */
final class RecipeRepository extends EntityRepository
{
    /**
     * @param UserInterface $user
     * @param bool $privateOnly
     * @return Recipe[]
     */
    public function getForUser(UserInterface $user, bool $privateOnly): array
    {
        $qb = $this->createQueryBuilder('r')
            ->select('r', 'c')
            ->join('r.userRecipe', 'ur')
            ->join('r.courses', 'c')
            ->where('ur.user = :userId')
            ->orderBy('r.dateAdd', 'DESC')
            ->setParameter('userId', $user->getUserIdentifier());
        if (!$privateOnly) {
            $qb->orWhere('ur.private = false');
        }
        return $qb->getQuery()->getResult();
    }

    /**
     * @param int $id
     * @throws NoResultException|NonUniqueResultException
     * @return Recipe
     */
    public function getOwnership(int $id): Recipe
    {
        return $this->createQueryBuilder('r')
            ->select('r', 'ur')
            ->join('r.userRecipe', 'ur')
            ->where('r.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getSingleResult();
    }

    /**
     * @param int $id
     * @throws NoResultException
     * @throws NonUniqueResultException
     * @return Recipe
     */
    public function getDetail(int $id): Recipe
    {
        return $this->createQueryBuilder('r')
            ->select('r', 'c', 'ur', 'ri', 's', 'i')
            ->join('r.userRecipe', 'ur')
            ->join('r.courses', 'c')
            ->join('r.recipeIngredients', 'ri')
            ->join('r.steps', 's')
            ->join('ri.ingredient', 'i')
            ->where('r.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getSingleResult();
    }
}
