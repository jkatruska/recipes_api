<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Ingredient;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

final class IngredientFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $ingredients = [
            [
                'name' => 'Vajce',
                'unit' => 'ks',
            ],
            [
                'name' => 'Cibuľa',
                'unit' => 'ks',
            ],
            [
                'name' => 'Soľ',
                'unit' => 'g',
            ],
        ];
        foreach ($ingredients as $ingredient) {
            $entity = new Ingredient();
            $entity->setName($ingredient['name']);
            $entity->setUnit($ingredient['unit']);
            $manager->persist($entity);
        }
        $manager->flush();
    }
}
