<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\User;
use App\Exception\ValidationException;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class AuthService
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private UserPasswordHasherInterface $passwordHasher,
        private ValidatorInterface $validator
    ) {
    }

    /**
     * @param array<string, string> $data
     * @throws ValidationException
     */
    public function register(array $data): void
    {
        $this->validateRegistration($data);
        $user = new User();
        $password = $this->passwordHasher->hashPassword($user, $data['password']);

        $user->setUsername($data['username']);
        $user->setPassword($password);
        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }

    /**
     * @param array<string, string> $data
     * @throws ValidationException
     */
    private function validateRegistration(array $data): void
    {
        $constraints = new Collection(
            fields: [
                'username' => new NotBlank(),
                'password' => new NotBlank(),
            ],
            allowExtraFields: false,
            allowMissingFields: false
        );
        $errors = $this->validator->validate($data, $constraints);
        if (count($errors)) {
            $e = new ValidationException('Invalid data provided');
            $e->setConstraintViolationList($errors);
            throw $e;
        }
    }
}
