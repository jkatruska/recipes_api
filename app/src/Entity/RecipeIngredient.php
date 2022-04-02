<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'recipe_ingredients')]
final class RecipeIngredient
{
    #[ORM\Column(name: 'amount', type: 'float', precision: 5, scale: 2, options: ['unsigned' => true])]
    private float $amount;

    #[ORM\Id]
    #[ORM\ManyToOne(targetEntity: Recipe::class, inversedBy: 'recipeIngredients')]
    #[ORM\JoinColumn(name: 'recipe_id', referencedColumnName: 'id', nullable: false)]
    private Recipe $recipe;

    #[ORM\Id]
    #[ORM\ManyToOne(targetEntity: Ingredient::class, inversedBy: 'recipeIngredient')]
    #[ORM\JoinColumn(name: 'ingredient_id', referencedColumnName: 'id', nullable: false)]
    private Ingredient $ingredient;

    /**
     * @return float
     */
    public function getAmount(): float
    {
        return $this->amount;
    }

    /**
     * @param float $amount
     */
    public function setAmount(float $amount): void
    {
        $this->amount = $amount;
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
     * @return Ingredient
     */
    public function getIngredient(): Ingredient
    {
        return $this->ingredient;
    }

    /**
     * @param Ingredient $ingredient
     */
    public function setIngredient(Ingredient $ingredient): void
    {
        $this->ingredient = $ingredient;
    }
}
