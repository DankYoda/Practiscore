<?php
declare(strict_types=1);

namespace App\Model\Entity;

use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Link;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use App\Service\State\Provider\Score\ScoreGetCollectionProvider;
use App\Service\State\Provider\Score\ScoreGetProvider;
use App\Service\State\Provider\Score\ScorePostProvider;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Symfony\Component\Uid\Uuid;

#[Get(
	uriTemplate: '/gathering/{idGather}/user/{idUser}/score/{id}',
	uriVariables: [
		'idGather' => new Link(toProperty: 'gathering', fromClass: Gathering::class),
        'idUser' => new Link(toProperty: 'user', fromClass: User::class),
		'id' => new Link(fromClass: Score::class),
    ],
	provider: ScoreGetProvider::class
)]
#[GetCollection(
	uriTemplate: '/gathering/{idGather}/user/{idUser}/score',
	uriVariables: [
		'idGather' => new Link(toProperty: 'gathering', fromClass: Gathering::class),
        'idUser' => new Link(toProperty: 'user', fromClass: User::class),
    ],
	provider: ScoreGetCollectionProvider::class
)]
#[GetCollection(
	uriTemplate: '/gathering/{idGather}/score',
	uriVariables: [
        'idGather' => new Link(toProperty: 'gathering', fromClass: Gathering::class),
    ],
)]
#[Post(
	uriTemplate: '/gathering/{idGather}/user/{idUser}/score',
	uriVariables: [
        'idGather' => new Link(toProperty: 'gathering', fromClass: Gathering::class),
        'idUser' => new Link(toProperty: 'user', fromClass: User::class),
    ],
	provider: ScorePostProvider::class
)]
#[Patch(
	uriTemplate: '/gathering/{idGather}/user/{idUser}/score/{id}',
	uriVariables: [
		'idGather' => new Link(toProperty: 'gathering', fromClass: Gathering::class),
        'idUser' => new Link(toProperty: 'user', fromClass: User::class),
		'id' => new Link(fromClass: Score::class),
    ],
	provider: ScoreGetProvider::class
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

	public function getGathering(): Gathering
	{
		return $this->gathering;
	}

	public function getUser(): User
	{
		return $this->user;
	}

}
