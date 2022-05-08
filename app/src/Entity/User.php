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

    /** @var ArrayCollection<int, Photo>|Collection<int, Photo> */
    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Photo::class)]
    private Collection|ArrayCollection $photos;

    public function __construct()
    {
        $this->photos = new ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function setUsername(string $username): void
    {
        $this->username = $username;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    public function getRoles(): array
    {
        return array_merge($this->roles, ['ROLE_USER']);
    }

    public function addRole(string $role): void
    {
        $this->roles[] = $role;
    }

    public function eraseCredentials(): void
    {
    }

    public function getUserIdentifier(): string
    {
        return $this->username;
    }

    public function addPhoto(Photo $photo): void
    {
        $photo->setUser($this);
        $this->photos[] = $photo;
    }

    /**
     * @return ArrayCollection<int, Photo>|Collection<int, Photo>
     */
    public function getPhotos(): ArrayCollection|Collection
    {
        return $this->photos;
    }
}
