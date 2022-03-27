<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity]
#[ORM\Table(name: 'users')]
#[ORM\Index(fields: ['username', 'password'], name: 'user_login')]
#[ORM\UniqueConstraint(name: 'username', fields: ['username'])]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Column(name: 'id', type: 'integer')]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'AUTO')]
    private int $id;

    #[ORM\Column(name: 'username', type: 'string', length: 50)]
    private string $username;

    #[ORM\Column(name: 'password', type: 'string', length: 250)]
    private string $password;

    /** @var string[] */
    #[ORM\Column(name: 'roles', type: 'json')]
    private array $roles = [];

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * @param string $username
     */
    public function setUsername(string $username): void
    {
        $this->username = $username;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @param string $password
     */
    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    /**
     * @return string[]
     */
    public function getRoles(): array
    {
        return array_merge($this->roles, ['ROLE_USER']);
    }

    /**
     * @param string $role
     */
    public function addRole(string $role): void
    {
        $this->roles[] = $role;
    }

    /**
     * {@inheritDoc}
     */
    public function eraseCredentials(): void
    {
    }

    /**
     * @return string
     */
    public function getUserIdentifier(): string
    {
        return $this->username;
    }
}
