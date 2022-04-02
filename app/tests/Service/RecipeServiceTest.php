<?php

declare(strict_types=1);

namespace App\Tests\Service;

use App\Entity\Course;
use App\Entity\Recipe;
use App\Entity\User;
use App\Repository\RecipeRepository;
use App\Service\RecipeService;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class RecipeServiceTest extends TestCase
{
    public function testListOfRecipes(): void
    {
        $recipes = [];

        $mainCourse = new Course();
        $mainCourse->setId(1);
        $mainCourse->setName('Main');

        $dinnerCourse = new Course();
        $dinnerCourse->setId(2);
        $dinnerCourse->setName('Dinner');

        $recipes[] = $this->createRecipe(1, 'Chilli', [$mainCourse, $dinnerCourse]);
        $recipes[] = $this->createRecipe(2, 'Potkan na vínovej omáčke', [$mainCourse]);

        $user = new User();

        $recipesRepo = $this->createStub(RecipeRepository::class);
        $recipesRepo->method('getForUser')
            ->willReturn($recipes);

        $entityManagerStub = $this->createStub(EntityManagerInterface::class);
        $entityManagerStub->method('getRepository')
            ->willReturn($recipesRepo);
        $validatorStub = $this->createStub(ValidatorInterface::class);

        $recipeService = new RecipeService($entityManagerStub, $validatorStub);
        $actualRecipes = $recipeService->getRecipes($user, false);

        self::assertCount(2, $actualRecipes);
        self::assertEquals('Main', $actualRecipes[0]->getCourses()->first()->getName());
        self::assertEquals('Potkan na vínovej omáčke', $actualRecipes[1]->getName());
    }

    public function testEmptyListOfRecipes(): void
    {
        $recipes = [];

        $user = new User();

        $recipesRepo = $this->createStub(RecipeRepository::class);
        $recipesRepo->method('getForUser')
            ->willReturn($recipes);

        $stub = $this->createStub(EntityManagerInterface::class);
        $stub->method('getRepository')
            ->willReturn($recipesRepo);
        $validatorStub = $this->createStub(ValidatorInterface::class);

        $recipeService = new RecipeService($stub, $validatorStub);
        $actualRecipes = $recipeService->getRecipes($user, false);

        self::assertCount(0, $actualRecipes);
    }

    /**
     * @param int $id
     * @param string $name
     * @param Course[] $courses
     * @return Recipe
     */
    private function createRecipe(int $id, string $name, array $courses): Recipe
    {
        $recipe = new Recipe();
        $recipe->setId($id);
        $recipe->setName($name);
        foreach ($courses as $course) {
            $recipe->addCourse($course);
        }
        return $recipe;
    }
}
