<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'recipe_steps')]
class RecipeStep
{
    #[ORM\Id]
    #[ORM\Column(name: 'id', type: 'integer')]
    #[ORM\GeneratedValue(strategy: 'AUTO')]
    private int $id;

    #[ORM\Column(name: 'step', type: 'integer', options: ['unsigned' => true])]
    private int $step;

    #[ORM\Column(name: 'text', type: 'text')]
    private string $text;

    #[ORM\ManyToOne(targetEntity: Recipe::class, inversedBy: 'steps')]
    #[ORM\JoinColumn(name: 'recipe_id', referencedColumnName: 'id', nullable: false)]
    private Recipe $recipe;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return int
     */
    public function getStep(): int
    {
        return $this->step;
    }

    /**
     * @param int $step
     */
    public function setStep(int $step): void
    {
        $this->step = $step;
    }

    /**
     * @return string
     */
    public function getText(): string
    {
        return $this->text;
    }

    /**
     * @param string $text
     */
    public function setText(string $text): void
    {
        $this->text = $text;
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
}
