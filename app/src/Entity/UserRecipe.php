<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'user_recipes')]
#[ORM\Index(fields: ['private'], name: 'private')]
final class UserRecipe
{
    #[ORM\Column(name: 'private', type: 'boolean')]
    private bool $private;

    #[ORM\Id]
    #[ORM\ManyToOne(targetEntity: Recipe::class, inversedBy: 'userRecipe')]
    #[ORM\JoinColumn(name: 'recipe_id', referencedColumnName: 'id', nullable: false)]
    private Recipe $recipe;

    #[ORM\Id]
    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'userRecipe')]
    #[ORM\JoinColumn(name: 'user_id', referencedColumnName: 'id', nullable: false)]
    private User $user;

    /**
     * @return bool
     */
    public function isPrivate(): bool
    {
        return $this->private;
    }

    /**
     * @param bool $private
     */
    public function setPrivate(bool $private): void
    {
        $this->private = $private;
    }

    /**
     * @return Recipe
     */
    public function getRecipe(): Recipe
    {
        return $this->recipe;
    }

    /**
     * @param Recipe $recipe
     */
    public function setRecipe(Recipe $recipe): void
    {
        $this->recipe = $recipe;
    }

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @param User $user
     */
    public function setUser(User $user): void
    {
        $this->user = $user;
    }
}
