<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\RecipeRepository;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RecipeRepository::class)]
#[ORM\Table(name: 'recipes')]
class Recipe
{
    #[ORM\Id]
    #[ORM\Column(name: 'id', type: 'integer')]
    #[ORM\GeneratedValue(strategy: 'AUTO')]
    private int $id;

    #[ORM\Column(name: 'name', type: 'string', length: 256)]
    private string $name;

    #[ORM\Column(name: 'date_add', type: 'datetime')]
    private DateTime $dateAdd;

    /** @var Collection<int, Course> */
    #[ORM\ManyToMany(targetEntity: Course::class)]
    #[ORM\JoinTable(name: 'recipe_courses')]
    #[ORM\JoinColumn(name: 'recipe_id', referencedColumnName: 'id')]
    #[ORM\InverseJoinColumn(name: 'course_id', referencedColumnName: 'id')]
    private Collection $courses;

    /** @var Collection<int, UserRecipe> */
    #[ORM\OneToMany(mappedBy: 'recipe', targetEntity: UserRecipe::class)]
    private Collection $userRecipe;

    /** @var Collection<int, RecipeIngredient> */
    #[ORM\OneToMany(mappedBy: 'recipe', targetEntity: RecipeIngredient::class)]
    private Collection $recipeIngredients;

    /** @var Collection<int, RecipeStep> */
    #[ORM\OneToMany(mappedBy: 'recipe', targetEntity: RecipeStep::class)]
    private Collection $steps;

    public function __construct()
    {
        $this->courses = new ArrayCollection();
        $this->userRecipe = new ArrayCollection();
        $this->recipeIngredients = new ArrayCollection();
        $this->steps = new ArrayCollection();
        $this->dateAdd = new DateTime();
    }

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
     * @return Collection<int, Course>
     */
    public function getCourses(): Collection
    {
        return $this->courses;
    }

    /**
     * @param Course $course
     */
    public function addCourse(Course $course): void
    {
        $this->courses[] = $course;
    }

    /**
     * @param Course $course
     */
    public function removeCourse(Course $course): void
    {
        if ($this->courses->contains($course)) {
            $this->courses->removeElement($course);
        }
    }

    /**
     * @return Collection<int, UserRecipe>
     */
    public function getUserRecipe(): Collection
    {
        return $this->userRecipe;
    }

    /**
     * @param UserRecipe $userRecipe
     */
    public function addUserRecipe(UserRecipe $userRecipe): void
    {
        $this->userRecipe[] = $userRecipe;
    }

    /**
     * @param Collection<int, UserRecipe> $userRecipe
     */
    public function setUserRecipe(Collection $userRecipe): void
    {
        $this->userRecipe = $userRecipe;
    }

    /**
     * @return Collection<int, RecipeIngredient>
     */
    public function getRecipeIngredients(): Collection
    {
        return $this->recipeIngredients;
    }

    /**
     * @param Collection<int, RecipeIngredient> $recipeIngredients
     */
    public function setRecipeIngredients(Collection $recipeIngredients): void
    {
        $this->recipeIngredients = $recipeIngredients;
    }

    /**
     * @param RecipeIngredient $recipeIngredient
     */
    public function addRecipeIngredient(RecipeIngredient $recipeIngredient): void
    {
        $this->recipeIngredients[] = $recipeIngredient;
    }

    /**
     * @return Collection<int, RecipeStep>
     */
    public function getSteps(): Collection
    {
        return $this->steps;
    }

    /**
     * @param RecipeStep $step
     */
    public function addStep(RecipeStep $step): void
    {
        $this->steps[] = $step;
    }

    /**
     * @return DateTime
     */
    public function getDateAdd(): DateTime
    {
        return $this->dateAdd;
    }

    /**
     * @param DateTime $dateAdd
     */
    public function setDateAdd(DateTime $dateAdd): void
    {
        $this->dateAdd = $dateAdd;
    }
}
