<?php
declare(strict_types=1);

namespace App\Model\Entity;

use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Link;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Symfony\Component\Uid\Uuid;

#[Get(
	uriTemplate: '/user/{idUser}/score/{id}',
	uriVariables: [
        'idUser' => new Link(toProperty: 'user', fromClass: User::class),
		'id' => new Link(fromClass: Score::class),
    ],
)]
#[GetCollection(
	uriTemplate: '/user/{idUser}/score',
	uriVariables: [
        'idUser' => new Link(toProperty: 'user', fromClass: User::class),
    ],
)]
#[GetCollection(
	uriTemplate: '/gathering/{idGathering}/score',
	uriVariables: [
        'idGathering' => new Link(toProperty: 'gathering', fromClass: Gathering::class),
    ],
)]
#[Post(
	uriTemplate: '/user/{idUser}/score',
	uriVariables: [
        'idUser' => new Link(toProperty: 'user', fromClass: User::class),
    ],
)]
#[Patch(
	uriTemplate: '/user/{idUser}/score/{id}',
	uriVariables: [
        'idUser' => new Link(toProperty: 'user', fromClass: User::class),
		'id' => new Link(fromClass: Score::class),
    ],
)]
#[ORM\Entity]
class Score
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
	private int $alpha = 0;
	#[ORM\Column]
	private int $charlie = 0;
	#[ORM\Column]
	private int $delta = 0;
	#[ORM\Column]
	private int $mike = 0;
	#[ORM\Column]
	private int $noShoot = 0;
	#[ManyToOne(targetEntity: User::class, inversedBy: 'scores')]
	#[JoinColumn(name: 'id_user', referencedColumnName: 'id', nullable: false)]
	private User $user;
	#[ManyToOne(targetEntity: Gathering::class, inversedBy: 'scores')]
	#[JoinColumn(name: 'id_gathering', referencedColumnName: 'id', nullable: false)]
	private Gathering $gathering;

	public function __construct()
    {
        $this->id = Uuid::v4()->toRfc4122();
    }

	public function getId(): string
	{
		return $this->id;
	}

	public function getAlpha(): int
	{
		return $this->alpha;
	}

	public function setAlpha(int $alpha): void
	{
		$this->alpha = $alpha;
	}

	public function getCharlie(): int
	{
		return $this->charlie;
	}

	public function setCharlie(int $charlie): void
	{
		$this->charlie = $charlie;
	}

	public function getDelta(): int
	{
		return $this->delta;
	}

	public function setDelta(int $delta): void
	{
		$this->delta = $delta;
	}

	public function getMike(): int
	{
		return $this->mike;
	}

	public function setMike(int $mike): void
	{
		$this->mike = $mike;
	}

	public function getNoShoot(): int
	{
		return $this->noShoot;
	}

	public function setNoShoot(int $noShoot): void
	{
		$this->noShoot = $noShoot;
	}
}