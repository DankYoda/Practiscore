<?php
declare(strict_types=1);

namespace App\Model\Entity;

use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Link;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use App\Service\State\Provider\Registration\RegistrationPostProvider;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity]
#[Get(
	uriTemplate: '/user/{idUser}/registration/{id}',
	uriVariables: [
        'idUser' => new Link(toProperty: 'user', fromClass: User::class),
		'id' => new Link(fromClass: Registration::class),
	],
)]
#[GetCollection(
	uriTemplate: '/user/{idUser}/registration',
    uriVariables: [
        'idUser' => new Link(toProperty: 'user', fromClass: User::class)
	],
)]
#[GetCollection(
	uriTemplate: '/gathering/{idGather}/registration',
    uriVariables: [
        'idGather' => new Link(toProperty: 'gathering', fromClass: Gathering::class)
	],
)]
#[Post(
    uriTemplate: '/gathering/{idGather}/registration',
	uriVariables: [
        'idGather' => new Link(toProperty: 'gathering', fromClass: Gathering::class),
	],
    provider: RegistrationPostProvider::class
)]
#[Patch(
    uriTemplate: '/user/{idUser}/registration/{id}',
	uriVariables: [
        'idUser' => new Link(toProperty: 'user', fromClass: User::class),
		'id' => new Link(fromClass: Registration::class),
	]
)]
#[Delete(
    uriTemplate: '/user/{idUser}/registration/{id}',
	uriVariables: [
        'idUser' => new Link(toProperty: 'user', fromClass: User::class),
		'id' => new Link(fromClass: Registration::class),
	]
)]
class Registration
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
	#[Groups(['user:default:read'])]
	private ?string $division;
	#[ORM\Column]
	#[Groups(['user:default:read'])]
	private ?string $classification;
	#[ORM\Column]
	#[Groups(['user:default:read'])]
	private ?string $powerFactor;
	#[ORM\Column]
	#[Groups(['user:default:read'])]
	private string $memberNumber = '';
	#[ORM\Column]
	#[Groups(['user:default:read'])]
	private int $category = 0;
	#[ORM\ManyToOne(targetEntity: User::class, inversedBy: "registrations")]
	#[ORM\JoinColumn(name: 'id_user', referencedColumnName: 'id')]
	private User $user;
	#[ORM\ManyToOne(targetEntity: Gathering::class, inversedBy: "registrations")]
	#[ORM\JoinColumn(name: 'id_gathering', referencedColumnName: 'id')]
	private Gathering $gathering;

	public function __construct(
		User $user,
		Gathering $gathering,
	)
	{
		$this->id = Uuid::v4()->toRfc4122();
		$this->user = $user;
		$this->gathering = $gathering;
	}
	
	public function getId(): string
	{
		return $this->id;
	}
	
	public function getDivision(): ?string
	{
		return $this->division;
	}
	
	public function setDivision(?string $division): void
	{
		$this->division = $division;
	}
	
	public function getClassification(): ?string
	{
		return $this->classification;
	}
	
	public function setClassification(?string $classification): void
	{
		$this->classification = $classification;
	}
	
	public function getPowerFactor(): ?string
	{
		return $this->powerFactor;
	}
	
	public function setPowerFactor(?string $powerFactor): void
	{
		$this->powerFactor = $powerFactor;
	}
	
	public function getMemberNumber(): string
	{
		return $this->memberNumber;
	}
	
	public function setMemberNumber(string $memberNumber): void
	{
		$this->memberNumber = $memberNumber;
	}
	
	public function getCategory(): int
	{
		return $this->category;
	}
	
	public function setCategory(int $category): void
	{
		$this->category = $category;
	}
	
	public function getUser(): User
	{
		return $this->user;
	}
	
	public function setUser(User $user): void
	{
		$this->user = $user;
	}
	
	public function getGathering(): Gathering
	{
		return $this->gathering;
	}
	
	public function setGathering(Gathering $gathering): void
	{
		$this->gathering = $gathering;
	}
}