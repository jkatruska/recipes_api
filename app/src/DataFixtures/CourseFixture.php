<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Course;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

final class CourseFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $courses = ['Raňajky', 'Brunch', 'Polievka', 'Hlavné jedlo', 'Večera', 'Dezert'];
        foreach ($courses as $course) {
            $entity = new Course();
            $entity->setName($course);
            $manager->persist($entity);
        }
        $manager->flush();
    }
}
