<?php
declare(strict_types=1);

namespace App\Model\Entity;

use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Link;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use App\Model\EmailChangeInput;
use App\Model\PasswordChangeInput;
use App\Model\PasswordResetInput;
use App\Model\UserRegistration;
use App\Service\State\Processor\User\ChangePasswordProcessor;
use App\Service\State\Processor\User\PasswordResetProcessor;
use App\Service\State\Processor\User\ResendVerifyEmailProcessor;
use App\Service\State\Processor\User\SendResetPasswordProcessor;
use App\Service\State\Processor\User\UserChangeEmailProcessor;
use App\Service\State\Processor\User\UserRegisterProcessor;
use App\Service\State\Processor\User\UserVerifyProcessor;
use App\Service\State\Provider\User\SendPasswordResetProvider;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\OneToOne;
use Symfony\Component\HttpFoundation\Response;
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
    security: "is_granted('ROLE_ADMIN') or object === user",
)]
#[GetCollection(
    uriTemplate: '/user',
    normalizationContext: ['groups' => ['user:default:read']],
    security: "is_granted('ROLE_ADMIN')"
)]
#[Patch(
    uriTemplate: '/user/{userId}',
    uriVariables: [
        'userId' => new Link(fromClass: User::class)
    ],
    denormalizationContext: ['groups' => ['user:patch:write']],
    security: "is_granted('ROLE_ADMIN') or object === user",
)]
#[Put(
	uriTemplate: '/user/{userId}/email/change',
	uriVariables: [
	    'userId' => new Link(fromClass: User::class)
	],
	security: "is_granted('ROLE_ADMIN') or object === user",
	input: EmailChangeInput::class,
	processor: UserChangeEmailProcessor::class,
)]
#[Put(
	uriTemplate: '/user/{userId}/email/verify',
    uriVariables: [
        'userId' => new Link(fromClass: User::class),
    ],
    deserialize: false,
    processor: UserVerifyProcessor::class
)]
#[Post(
	uriTemplate: '/user/{userId}/email/verify/send',
	uriVariables: [
		'userId' => new Link(fromClass: User::class),
	],
	status: Response::HTTP_NO_CONTENT,
	deserialize: false,
	processor: ResendVerifyEmailProcessor::class

)]
#[Put(
	uriTemplate: '/user/{userId}/password/change',
	uriVariables: [
		'userId' => new Link(fromClass: User::class),
	],
	security: "is_granted('ROLE_ADMIN') or object === user",
	input: PasswordChangeInput::class,
	processor: ChangePasswordProcessor::class
)]
#[Post(
	uriTemplate: '/user/{email}/password/reset/send',
    uriVariables: [
        'email' => new Link(fromClass: User::class),
    ],
    status: Response::HTTP_NO_CONTENT,
    deserialize: false,
    provider: SendPasswordResetProvider::class,
    processor: SendResetPasswordProcessor::class
)]
#[Put(
	uriTemplate: '/user/{userId}/password/reset',
    uriVariables: [
        'userId' => new Link(fromClass: User::class),
    ],
	input: PasswordResetInput::class,
	processor: PasswordResetProcessor::class
)]
#[Post(
	uriTemplate: '/user/register',
	status: Response::HTTP_CREATED,
	denormalizationContext: [
		'groups' => ['user:post:write:register']
	],
	input: UserRegistration::class,
	processor: UserRegisterProcessor::class
)]
class User implements UserInterface, PasswordUpgraderInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\Column(
        length: 36,
        options: [
		    'fixed' => true,
		]
    )]
    #[Groups(['user:default:read'])]
    private readonly string $id;
    #[ORM\Column]
    #[Groups([
		'user:default:read',
	    'user:post:write:register',
    ])]
    private string $firstName = '';
    #[ORM\Column]
    #[Groups([
		'user:default:read',
	    'user:post:write:register',
    ])]
    private string $lastName = '';
    #[ORM\Column(
        unique: true,
    )]
    #[Groups([
		'user:default:read',
		'user:post:write:register',
    ])]
    private string $username;
    #[ORM\Column(
        unique: true,
    )]
    #[Groups([
		'user:default:read',
	    'user:post:write:register',
    ])]
    #[Assert\Email]
    private string $email;
	#[ORM\Column]
	#[Groups([
		'user:default:read',
	])]
	private bool $emailVerified = false;
    #[ORM\Column(
        nullable: false
    )]
    private string $password = '';
    #[ORM\Column]
    #[Groups([
		'user:default:read',
	    'user:post:write:register',
    ])]
    private string $titles;
    #[ORM\Column]
    #[Groups([
		'user:default:read',
	    'user:post:write:register',
    ])]
    private string $guns = '';
    #[ORM\Column]
    #[Groups([
		'user:default:read',
	    'user:post:write:register',
    ])]
    private string $optics = '';
    #[ORM\Column]
    #[Groups([
		'user:default:read',
	    'user:post:write:register',
    ])]
    private string $ammo = '';
    #[ORM\Column]
    #[Groups([
		'user:default:read',
	    'user:post:write:register',
    ])]
    private string $instagramHandle = '';
    #[ORM\Column]
    #[Groups([
		'user:default:read',
	    'user:post:write:register',
    ])]
    private string $bio = '';
	#[ORM\Column]
	#[Groups(['user:default:read'])]
	private int $admin = 0;
	
	#[ORM\Column(
		type: Types::DATETIME_IMMUTABLE
	)]
	private ?DateTimeImmutable $passwordChanged = null;
	
	#[ORM\Column(
		type: Types::DATETIME_IMMUTABLE,
		nullable: true
	)]
	private ?DateTimeImmutable $notBefore = null;
	
	#[OneToOne(targetEntity: Club::class)]
	#[JoinColumn(name: 'id_home_club', referencedColumnName: 'id', nullable: true)]
	#[Groups(['user:default:read'])]
	private ?Club $homeClub = null;
	#[OneToOne(targetEntity: Club::class)]
	#[JoinColumn(name: 'id_managed_club', referencedColumnName: 'id', nullable: true)]
	#[Groups(['user:default:read'])]
	private ?Club $managedClub = null;
	
	#[ORM\OneToMany(targetEntity: Registration::class, mappedBy: 'user')]
	#[Groups(['user:default:read'])]
	private Collection $registrations;

    public function __construct(
        string $username,
        string $email,
        ?\DateTimeImmutable $passwordChanged = null,
    )
    {
        $this->id = Uuid::v4()->toRfc4122();
        $this->username = $username;
        $this->email = $email;
        if ($passwordChanged) $this->setPasswordChanged($passwordChanged);
		$this->registrations = new ArrayCollection();
    }
    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void
    {
		
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
		$this->passwordChanged = new DateTimeImmutable();
		$this->notBefore = null;
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
	
	public function getRegistrations(): Collection
	{
		return $this->registrations;
	}
	
	public function setPasswordChanged(DateTimeImmutable $now): void
	{
		$this->passwordChanged = $now;
		$this->setNotBefore($now);
	}
	
	public function getPasswordChanged(): DateTimeImmutable
	{
		return $this->passwordChanged;
	}
	
	public function isEmailVerified(): bool
	{
		return $this->emailVerified;
	}
	
	public function setEmailVerified(bool $emailVerified): void
	{
		$this->emailVerified = $emailVerified;
	}
	
	public function getNotBefore(): ?DateTimeImmutable
	{
		return $this->notBefore;
	}
	
	public function setNotBefore(?DateTimeImmutable $notBefore): void
	{
		$this->notBefore = $notBefore;
	}
}
