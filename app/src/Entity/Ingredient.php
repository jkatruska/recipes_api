<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'ingredients')]
#[ORM\Index(fields: ['unit'], name: 'unit')]
class Ingredient
{
    #[ORM\Id]
    #[ORM\Column(name: 'id', type: 'integer')]
    #[ORM\GeneratedValue(strategy: 'AUTO')]
    private int $id;

    #[ORM\Column(name: 'name', type: 'string', length: 128)]
    private string $name;

    #[ORM\Column(name: 'unit', type: 'string', length: 8)]
    private string $unit;

    /** @var Collection<int, RecipeIngredient> */
    #[ORM\OneToMany(mappedBy: 'recipe', targetEntity: RecipeIngredient::class)]
    private Collection $recipeIngredient;

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
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getUnit(): string
    {
        return $this->unit;
    }

    /**
     * @param string $unit
     */
    public function setUnit(string $unit): void
    {
        $this->unit = $unit;
    }

    /**
     * @return Collection<int, RecipeIngredient>
     */
    public function getRecipeIngredient(): Collection
    {
        return $this->recipeIngredient;
    }

    /**
     * @param Collection<int, RecipeIngredient> $recipeIngredient
     */
    public function setRecipeIngredient(Collection $recipeIngredient): void
    {
        $this->recipeIngredient = $recipeIngredient;
    }
}
