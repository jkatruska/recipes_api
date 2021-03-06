<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Course;
use App\Entity\Ingredient;
use App\Entity\Photo;
use App\Entity\Recipe;
use App\Entity\RecipeIngredient;
use App\Entity\RecipeStep;
use App\Entity\User;
use App\Entity\UserRecipe;
use App\Exception\PermissionException;
use App\Exception\ServerException;
use App\Exception\ValidationException;
use App\Repository\RecipeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints\All;
use Symfony\Component\Validator\Constraints\Choice;
use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Constraints\Optional;
use Symfony\Component\Validator\Constraints\Type;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class RecipeService
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private ValidatorInterface $validator
    ) {
    }

    /**
     * @param UserInterface $user
     * @param bool $privateOnly
     * @return Recipe[]
     */
    public function getRecipes(UserInterface $user, bool $privateOnly): array
    {
        /** @var RecipeRepository $recipeRepo */
        $recipeRepo = $this->entityManager->getRepository(Recipe::class);
        return $recipeRepo->getForUser($user, $privateOnly);
    }

    /**
     * @param UserInterface $user
     * @param array<string, mixed> $data
     * @throws ValidationException|ServerException
     */
    public function addRecipe(UserInterface $user, array $data): void
    {
        $this->validateRecipe($data, $user);

        $recipe = new Recipe();
        $recipe->setName($data['name']);

        $userRecipe = new UserRecipe();
        $userRecipe->setRecipe($recipe);
        $userRecipe->setPrivate($data['private']);
        if ($user instanceof User) {
            $userRecipe->setUser($user);
        }

        $recipe->addUserRecipe($userRecipe);

        $this->addCourses($recipe, $data['courseIds']);
        $this->addIngredients($recipe, $data['ingredients']);
        $this->addSteps($recipe, $data['steps']);

        $this->entityManager->persist($recipe);
        $this->entityManager->persist($userRecipe);
        $this->entityManager->flush();
    }

    /**
     * @param UserInterface $user
     * @param int $id
     * @throws PermissionException|NoResultException
     * @throws NonUniqueResultException
     * @return Recipe
     */
    public function getRecipe(UserInterface $user, int $id): Recipe
    {
        if (!$this->isAuthorized($user, $id)) {
            throw new PermissionException("Recipe id: $id doesn't belong to logged user nor is public");
        }
        /** @var RecipeRepository $recipeRepo */
        $recipeRepo = $this->entityManager->getRepository(Recipe::class);
        return $recipeRepo->getDetail($id);
    }

    /**
     * @param UserInterface $user
     * @param int $id
     * @throws NoResultException
     * @return bool
     */
    private function isAuthorized(UserInterface $user, int $id): bool
    {
        /** @var RecipeRepository $recipeRepo */
        $recipeRepo = $this->entityManager->getRepository(Recipe::class);
        $recipe = $recipeRepo->getOwnership($id);
        /** @var UserRecipe $userRecipe */
        foreach ($recipe->getUserRecipe() as $userRecipe) {
            if ($userRecipe->getUser()->getId() == $user->getUserIdentifier() || !$userRecipe->isPrivate()) {
                return true;
            }
        }
        return false;
    }

    /**
     * @param array<string, mixed> $data
     * @param UserInterface $user
     * @throws ValidationException
     */
    private function validateRecipe(array $data, UserInterface $user): void
    {
        $courseIds = $this->getValidCourseIds();
        $ingredientIds = $this->getValidIngredientIds();
        $userPhotoKeys = $this->getValidPhotoKeys($user);
        $constraints = new Collection(
            fields: [
                'name' => [
                    new NotBlank(),
                    new Length(max: 256),
                ],
                'courseIds' => [
                    new NotBlank(),
                    new Choice(choices: $courseIds, multiple: true),
                ],
                'private' => [
                    new NotNull(),
                    new Type(type: 'bool'),
                ],
                'photoId' => [
                    new NotBlank(),
                    new Choice(choices: $userPhotoKeys),
                ],
                'ingredients' => [
                    new All([
                        new Collection(
                            fields: [
                                'id' => [
                                    new NotBlank(),
                                    new Choice(choices: $ingredientIds),
                                ],
                                'amount' => [
                                    new NotBlank(),
                                    new Type('numeric'),
                                ],
                            ],
                            allowExtraFields: false,
                            allowMissingFields: false
                        ),
                    ]),
                ],
                'steps' => [
                    new All([
                        new Collection(
                            fields: [
                                'number' => new NotBlank(),
                                'text' => new NotBlank(),
                                'photoId' => new Optional(),
                            ],
                            allowExtraFields: false,
                            allowMissingFields: false
                        ),
                    ]),
                ],
            ],
            allowExtraFields: false,
            allowMissingFields: false,
        );
        $errors = $this->validator->validate($data, $constraints);
        if (count($errors)) {
            $e = new ValidationException('Invalid data provided');
            $e->setConstraintViolationList($errors);
            throw $e;
        }
    }

    /**
     * @return int[]
     */
    private function getValidCourseIds(): array
    {
        $courseRepo = $this->entityManager->getRepository(Course::class);
        /** @var Course[] $courses */
        $courses = $courseRepo->findAll();
        return array_map(fn ($course) => $course->getId(), $courses);
    }

    /**
     * @return int[]
     */
    private function getValidIngredientIds(): array
    {
        $ingredientsRepo = $this->entityManager->getRepository(Ingredient::class);
        /** @var Ingredient[] $ingredients */
        $ingredients = $ingredientsRepo->findAll();
        return array_map(fn ($ingredient) => $ingredient->getId(), $ingredients);
    }

    /**
     * @return int[]
     */
    private function getValidPhotoKeys(UserInterface $user): array
    {
        assert($user instanceof User);
        $photosRepo = $this->entityManager->getRepository(Photo::class);
        /** @var Photo[] $photos */
        $photos = $photosRepo->findBy(['user' => $user]);
        return array_map(fn ($photo) => $photo->getKey(), $photos);
    }

    /**
     * @param Recipe $recipe
     * @param int[] $courseIds
     * @throws ServerException
     */
    private function addCourses(Recipe $recipe, array $courseIds): void
    {
        try {
            foreach ($courseIds as $courseId) {
                $course = $this->entityManager->getReference(Course::class, $courseId);
                $recipe->addCourse($course);
            }
        } catch (\Doctrine\ORM\ORMException|ORMException) {
            // TODO: logger
            throw new ServerException();
        }
    }

    /**
     * @param Recipe $recipe
     * @param array<int, array> $ingredients
     * @throws ServerException
     */
    private function addIngredients(Recipe $recipe, array $ingredients): void
    {
        try {
            foreach ($ingredients as $recipeIngredient) {
                /** @var Ingredient $ingredient */
                $ingredient = $this->entityManager->getReference(Ingredient::class, $recipeIngredient['id']);
                $recipeIngredientEntity = new RecipeIngredient();
                $recipeIngredientEntity->setRecipe($recipe);
                $recipeIngredientEntity->setAmount($recipeIngredient['amount']);
                $recipeIngredientEntity->setIngredient($ingredient);
                $this->entityManager->persist($recipeIngredientEntity);
                $recipe->addRecipeIngredient($recipeIngredientEntity);
            }
        } catch (\Doctrine\ORM\ORMException|ORMException) {
            // TODO: logger
            throw new ServerException();
        }
    }

    /**
     * @param Recipe $recipe
     * @param array<int, string|int> $steps
     */
    private function addSteps(Recipe $recipe, array $steps): void
    {
        foreach ($steps as $step) {
            $recipeStep = new RecipeStep();
            $recipeStep->setRecipe($recipe);
            $recipeStep->setStep($step['number']);
            $recipeStep->setText($step['text']);
            $this->entityManager->persist($recipeStep);
            $recipe->addStep($recipeStep);
        }
    }
}
