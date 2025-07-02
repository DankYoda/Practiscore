<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity]
#[ApiResource]
class User implements UserInterface, PasswordUpgraderInterface
{
    #[ORM\Id]
    #[ORM\Column(
        length: 36,
        options: [
		    'fixed' => true,
		]
    )]
    private string $id {
        get => $this->id;
    }
    #[ORM\Column]
    private string $firstName = ''{
        get => $this->firstName;
        set => $this->firstName;
    }
    #[ORM\Column]
    private string $lastName = ''{
        get => $this->lastName;
        set => $this->lastName;
    }
    #[ORM\Column(
        unique: true,
    )]
    private string $username {
        get => $this->username;
    }
    #[ORM\Column(
        unique: true,
    )]
    #[Assert\Email]
    private string $email {
        get => $this->email;
    }
    #[ORM\Column]
    private string $password = '' {
        get {
            return $this->password;
        }
        set {
            $this->password = $value;
        }
    }
    #[ORM\Column]
    private string $titles {
        get => $this->titles;
        set {
            $this->titles = $value;
        }
    }
    #[ORM\Column]
    private string $guns = '' {
        get {
            return $this->guns;
        }
        set {
            $this->guns = $value;
        }
    }
    #[ORM\Column]
    private string $optics = '' {
        get {
            return $this->optics;
        }
        set {
            $this->optics = $value;
        }
    }
    #[ORM\Column]
    private string $ammo = '' {
        get {
            return $this->ammo;
        }
        set {
            $this->ammo = $value;
        }
    }
    #[ORM\Column]
    private string $instagramHandle = '' {
        get {
            return $this->instagramHandle;
        }
        set {
            $this->instagramHandle = $value;
        }
    }
    #[ORM\Column]
    private string $bio = '' {
        get {
            return $this->bio;
        }
        set {
            $this->bio = $value;
        }
    }

    public function __construct(
        string $username,
        string $email,
    )
    {
        $this->id = Uuid::v4()->toRfc4122();
        $this->username = $username;
        $this->email = $email;
    }
    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void
    {
        // TODO: Implement upgradePassword() method.
    }

    public function getRoles(): array
    {
        // TODO: Implement getRoles() method.
        return ['ROLE_USER'];
    }

    public function eraseCredentials(): void
    {
        $this->password = '';
    }

    public function getUserIdentifier(): string
    {
        return $this->username;
    }
}
