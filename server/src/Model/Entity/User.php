<?php
declare(strict_types=1);

namespace App\Model\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Link;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\OneToOne;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity]
#[Get(
    uriTemplate: '/user/{id}',
    uriVariables: [
        'id' => new Link(fromClass: User::class),
    ],
    normalizationContext: ['groups' => ['user:default:read']],
)]
#[GetCollection(
    uriTemplate: '/user',
	normalizationContext: ['groups' => ['user:default:read']],
)]
class User implements UserInterface, PasswordUpgraderInterface
{
    #[ORM\Id]
    #[ORM\Column(
        length: 36,
        options: [
		    'fixed' => true,
		]
    )]
    #[Groups(['user:default:read'])]
    private string $id;
    #[ORM\Column]
    #[Groups(['user:default:read'])]
    private string $firstName = '';
    #[ORM\Column]
    #[Groups(['user:default:read'])]
    private string $lastName = '';
    #[ORM\Column(
        unique: true,
    )]
    #[Groups(['user:default:read'])]
    private string $username;
    #[ORM\Column(
        unique: true,
    )]
    #[Groups(['user:default:read'])]
    #[Assert\Email]
    private string $email;
    #[ORM\Column]
    private string $password = '';
    #[ORM\Column]
    #[Groups(['user:default:read'])]
    private string $titles;
    #[ORM\Column]
    #[Groups(['user:default:read'])]
    private string $guns = '';
    #[ORM\Column]
    #[Groups(['user:default:read'])]
    private string $optics = '';
    #[ORM\Column]
    #[Groups(['user:default:read'])]
    private string $ammo = '';
    #[ORM\Column]
    #[Groups(['user:default:read'])]
    private string $instagramHandle = '';
    #[ORM\Column]
    #[Groups(['user:default:read'])]
    private string $bio = '';
	#[ORM\Column]
	#[Groups(['user:default:read'])]
	private int $admin = 0;
	
	#[OneToOne(targetEntity: Club::class)]
	#[JoinColumn(name: 'id_home_club', referencedColumnName: 'id', nullable: true)]
	#[Groups(['user:default:read'])]
	private ?Club $homeClub = null;
	#[OneToOne(targetEntity: Club::class)]
	#[JoinColumn(name: 'id_managed_club', referencedColumnName: 'id', nullable: true)]
	#[Groups(['user:default:read'])]
	private ?Club $managedClub = null;

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
	#[Groups(['user:default:read'])]
    public function getRoles(): array
    {
        $roles = ['ROLE_USER'];
		if ($this->admin)
			$roles[] = 'ROLE_ADMIN';
		if ($this->managedClub)
			$roles[] = 'ROLE_MANAGER';
        return $roles;
    }

    public function eraseCredentials(): void
    {
        $this->password = '';
    }

    public function getUserIdentifier(): string
    {
        return $this->username;
    }

	public function getId(): string
	{
		return $this->id;
	}

	public function getFirstName(): string
	{
		return $this->firstName;
	}

	public function setFirstName(string $firstName): void
	{
		$this->firstName = $firstName;
	}

	public function getLastName(): string
	{
		return $this->lastName;
	}

	public function setLastName(string $lastName): void
	{
		$this->lastName = $lastName;
	}

	public function getUsername(): string
	{
		return $this->username;
	}

	public function getEmail(): string
	{
		return $this->email;
	}

	public function setEmail(string $email): void
	{
		$this->email = $email;
	}

	public function getPassword(): string
	{
		return $this->password;
	}

	public function setPassword(string $password): void
	{
		$this->password = $password;
	}

	public function getTitles(): string
	{
		return $this->titles;
	}

	public function setTitles(string $titles): void
	{
		$this->titles = $titles;
	}

	public function getGuns(): string
	{
		return $this->guns;
	}

	public function setGuns(string $guns): void
	{
		$this->guns = $guns;
	}

	public function getOptics(): string
	{
		return $this->optics;
	}

	public function setOptics(string $optics): void
	{
		$this->optics = $optics;
	}

	public function getAmmo(): string
	{
		return $this->ammo;
	}

	public function setAmmo(string $ammo): void
	{
		$this->ammo = $ammo;
	}

	public function getInstagramHandle(): string
	{
		return $this->instagramHandle;
	}

	public function setInstagramHandle(string $instagramHandle): void
	{
		$this->instagramHandle = $instagramHandle;
	}

	public function getBio(): string
	{
		return $this->bio;
	}

	public function setBio(string $bio): void
	{
		$this->bio = $bio;
	}
	
	public function isAdmin(): int
	{
		return $this->admin;
	}
	
	public function setAdmin(int $admin): void
	{
		$this->admin = $admin;
	}
	
	public function getHomeClub(): ?Club
	{
		return $this->homeClub;
	}
	
	public function setHomeClub(?Club $homeClub): void
	{
		$this->homeClub = $homeClub;
	}
	
	public function getManagedClub(): ?Club
	{
		return $this->managedClub;
	}
	
	public function setManagedClub(?Club $managedClub): void
	{
		$this->managedClub = $managedClub;
	}
}
