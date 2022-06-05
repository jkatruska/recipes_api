<?php

declare(strict_types=1);

namespace App\Domain\Normalizer\Json\Entity;

use App\Entity\Course;
use App\Entity\Recipe;
use App\Entity\RecipeIngredient;
use App\Entity\RecipeStep;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

final class RecipeNormalizer implements NormalizerInterface
{
    public const CONTEXT_LIST = 'list';
    public const CONTEXT_DETAIL = 'detail';

    /**
     * @param Recipe $object
     * @param string|null $format
     * @param string[] $context
     * @return array<string, mixed>
     */
    public function normalize(mixed $object, string $format = null, array $context = []): array
    {
        if (empty($context['render'])) {
            return $this->formatDataForList($object);
        }

        return match ($context['render']) {
            self::CONTEXT_DETAIL => $this->formatDataForDetail($object),
            default => $this->formatDataForList($object)
        };
    }

    /**
     * @param mixed $data
     * @param string|null $format
     * @return bool
     */
    public function supportsNormalization(mixed $data, string $format = null): bool
    {
        return $data instanceof Recipe && $format === 'json';
    }

    /**
     * @param Recipe $recipe
     * @return array<string, mixed>
     */
    private function formatDataForList(Recipe $recipe): array
    {
        $courses = [];

        /** @var Course $course */
        foreach ($recipe->getCourses() as $course) {
            $courses[] = $course->getName();
        }

        return [
            'id' => $recipe->getId(),
            'name' => $recipe->getName(),
            'courses' => $courses,
        ];
    }

    /**
     * @param Recipe $recipe
     * @return array<string, mixed>
     */
    private function formatDataForDetail(Recipe $recipe): array
    {
        $courses = $ingredients = $steps = [];

        /** @var Course $course */
        foreach ($recipe->getCourses() as $course) {
            $courses[] = [
                'id' => $course->getId(),
                'name' => $course->getName(),
            ];
        }

        /** @var RecipeIngredient $recipeIngredient */
        foreach ($recipe->getRecipeIngredients() as $recipeIngredient) {
            $ingredients[] = [
                'id' => $recipeIngredient->getIngredient()->getId(),
                'name' => $recipeIngredient->getIngredient()->getName(),
                'amount' => $recipeIngredient->getAmount(),
                'unit' => $recipeIngredient->getIngredient()->getUnit(),
            ];
        }

        /** @var RecipeStep $step */
        foreach ($recipe->getSteps() as $step) {
            $steps[] = [
                'id' => $step->getId(),
                'number' => $step->getStep(),
                'text' => $step->getText(),
            ];
        }

        return [
            'name' => $recipe->getName(),
            'private' => !empty($recipe->getUserRecipe()->first()) && $recipe->getUserRecipe()->first()->isPrivate(),
            'courses' => $courses,
            'ingredients' => $ingredients,
            'steps' => $steps,
        ];
    }
}
