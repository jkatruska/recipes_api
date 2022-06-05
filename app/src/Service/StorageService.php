<?php

declare(strict_types=1);

namespace App\Service;

use App\Domain\Storage\File;
use App\Domain\Storage\StorageInterface;
use App\Entity\Photo;
use App\Entity\User;
use App\Exception\ValidationException;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Validator\Constraints\Choice;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class StorageService
{
    public function __construct(
        private StorageInterface $storage,
        private ValidatorInterface $validator,
        private EntityManagerInterface $entityManager,
        private Security $security
    ) {
    }

    public function get(string $key): ?File
    {
        return $this->storage->get($key);
    }

    /**
     * @throws ValidationException
     */
    public function save(array $file): File
    {
        $this->validateImageUpload($file);
        $file = $this->storage->save($file);

        $user = $this->security->getUser();
        if ($user instanceof User) {
            $photo = new Photo();
            $photo->setKey($file->getKey());
            $photo->setUser($user);

            $this->entityManager->persist($photo);
            $this->entityManager->flush();
        }

        return $file;
    }

    /**
     * @throws ValidationException
     */
    private function validateImageUpload(array $file)
    {
        if (empty($file)) {
            throw new ValidationException('No file provided');
        }
        $constraint = new Choice([UPLOAD_ERR_OK]);
        $errors = $this->validator->validate($file['error'], $constraint);
        if (count($errors)) {
            throw new ValidationException();
        }
    }
}
