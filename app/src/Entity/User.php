<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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

    /** @var Collection<int, UserRecipe> */
    #[ORM\OneToMany(mappedBy: 'user', targetEntity: UserRecipe::class)]
    private Collection $userRecipe;

    public function __construct()
    {
        $this->userRecipe = new ArrayCollection();
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

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
        return (string) $this->id;
    }

    /**
     * @return Collection<int, UserRecipe>
     */
    public function getUserRecipe(): Collection
    {
        return $this->userRecipe;
    }

    /**
     * @param Collection<int, UserRecipe> $userRecipe
     */
    public function setUserRecipe(Collection $userRecipe): void
    {
        $this->userRecipe = $userRecipe;
    }
}
