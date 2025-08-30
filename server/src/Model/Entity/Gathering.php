<?php
declare(strict_types=1);

namespace App\Model\Entity;

use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Link;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use App\Service\State\Provider\Gathering\GatheringPatchProvider;
use App\Service\State\Provider\Gathering\GatheringPostProvider;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Symfony\Component\Uid\Uuid;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[Get(
	uriTemplate: '/club/{idClub}/gathering/{id}',
	uriVariables: [
		'idClub' => new Link(toProperty: 'club', fromClass: Club::class),
		'id' => new Link(fromClass: Gathering::class),
	],
)]
#[GetCollection(
	uriTemplate: '/gathering',
)]
#[Post(
	uriTemplate: '/club/{idClub}/gathering',
	uriVariables: [
		'idClub' => new Link(toProperty: 'club', fromClass: Club::class),
	],
	provider: GatheringPostProvider::class
)]
#[Patch(
	uriTemplate: '/club/{idClub}/gathering/{id}',
	uriVariables: [
		'idClub' => new Link(toProperty: 'club', fromClass: Club::class),
		'id' => new Link(fromClass: Gathering::class),
	],
	provider: GatheringPatchProvider::class
)]
class Gathering
{
	#[ORM\Id]
	#[ORM\Column(
		length: 36,
		options: [
			'fixed' => true,
		]
	)]
	private readonly string $id;
	#[ORM\Column]
	private string $address;
	#[ORM\Column]
	private string $state;
	#[ORM\Column]
	private string $city;
	#[ORM\Column]
	private string $country;
	#[ORM\Column]
	private string $description;
	#[ORM\Column(
		type: Types::DATETIME_IMMUTABLE
	)]
	private DateTimeImmutable $openDate;
	#[ORM\Column(
		type: Types::DATETIME_IMMUTABLE
	)]
	private DateTimeImmutable $closeDate;
	#[ORM\Column(
		type: Types::DATETIME_IMMUTABLE
	)]
	private DateTimeImmutable $startDate;
	#[ORM\Column(
		type: Types::DATETIME_IMMUTABLE
	)]
	private DateTimeImmutable $endDate;
	#[ORM\Column]
	private float $price = 0;
	#[ORM\Column]
	private string $waiver;
	#[ORM\Column]
	private string $eventType;
	#[ORM\Column]
	private string $matchType;
	#[ManyToOne(targetEntity: Club::class, inversedBy: 'gatherings')]
	#[JoinColumn(name: 'id_club', referencedColumnName: 'id', nullable: false)]
	private Club $club;
	
	#[ORM\OneToMany(targetEntity: Registration::class, mappedBy: 'gathering')]
	private Collection $registrations;

	#[ORM\OneToMany(targetEntity: Score::class, mappedBy: 'gathering')]
	private Collection $scores;
	
	public function __construct(
		Club $club,
	)
	{
		$this->id = Uuid::v4()->toRfc4122();
		$this->club = $club;
		$this->registrations = new ArrayCollection();
		$this->scores = new ArrayCollection();
	}
	
	public function getId(): string
	{
		return $this->id;
	}
	
	public function getAddress(): string
	{
		return $this->address;
	}
	
	public function setAddress(string $address): void
	{
		$this->address = $address;
	}
	
	public function getState(): string
	{
		return $this->state;
	}
	
	public function setState(string $state): void
	{
		$this->state = $state;
	}
	
	public function getCity(): string
	{
		return $this->city;
	}
	
	public function setCity(string $city): void
	{
		$this->city = $city;
	}
	
	public function getCountry(): string
	{
		return $this->country;
	}
	
	public function setCountry(string $country): void
	{
		$this->country = $country;
	}
	
	public function getDescription(): string
	{
		return $this->description;
	}
	
	public function setDescription(string $description): void
	{
		$this->description = $description;
	}
	
	public function getOpenDate(): DateTimeImmutable
	{
		return $this->openDate;
	}
	
	public function setOpenDate(DateTimeImmutable $openDate): void
	{
		$this->openDate = $openDate;
	}
	
	public function getCloseDate(): DateTimeImmutable
	{
		return $this->closeDate;
	}
	
	public function setCloseDate(DateTimeImmutable $closeDate): void
	{
		$this->closeDate = $closeDate;
	}
	
	public function getStartDate(): DateTimeImmutable
	{
		return $this->startDate;
	}
	
	public function setStartDate(DateTimeImmutable $startDate): void
	{
		$this->startDate = $startDate;
	}
	
	public function getEndDate(): DateTimeImmutable
	{
		return $this->endDate;
	}
	
	public function setEndDate(DateTimeImmutable $endDate): void
	{
		$this->endDate = $endDate;
	}
	
	public function getWaiver(): string
	{
		return $this->waiver;
	}
	
	public function setWaiver(string $waiver): void
	{
		$this->waiver = $waiver;
	}
	
	public function getEventType(): string
	{
		return $this->eventType;
	}
	
	public function setEventType(string $eventType): void
	{
		$this->eventType = $eventType;
	}
	
	public function getMatchType(): string
	{
		return $this->matchType;
	}
	
	public function setMatchType(string $matchType): void
	{
		$this->matchType = $matchType;
	}
	
	public function getClub(): ?Club
	{
		return $this->club;
	}
	
	public function getRegistrations(): Collection
	{
		return $this->registrations;
	}

	public function getScores(): Collection
	{
		return $this->scores;
	}
}