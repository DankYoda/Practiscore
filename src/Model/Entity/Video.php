<?php
declare(strict_types=1);

namespace App\Model\Entity;

use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Link;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use App\Service\State\Provider\Video\VideoPostProvider;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\OneToOne;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity]
#[Get(
	uriTemplate: '/user/{idUser}/video/{id}',
	uriVariables: [
		'idUser' => new Link(toProperty: 'user', fromClass: User::class),
		'id' => new Link(fromClass: Video::class),
	],
)]
#[GetCollection(
	uriTemplate: '/user/{idUser}/video',
	uriVariables: [
		'idUser' => new Link(toProperty: 'user', fromClass: User::class),
	]
)]
#[Post(
	uriTemplate: '/user/{idUser}/video',
	uriVariables: [
		'idUser' => new Link(toProperty: 'user', fromClass: User::class),
	],
	provider: VideoPostProvider::class,
)]
#[Patch(
	uriTemplate: '/user/{idUser}/video/{id}',
	uriVariables: [
		'idUser' => new Link(toProperty: 'user', fromClass: User::class),
		'id' => new Link(fromClass: Video::class),
	],
)]
#[Delete(
	uriTemplate: '/user/{idUser}/video/{id}',
	uriVariables: [
		'idUser' => new Link(toProperty: 'user', fromClass: User::class),
		'id' => new Link(fromClass: Video::class),
	],
)]
class Video
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
	private string $url;

	#[ORM\Column]
	private string $description;
	#[OneToOne(targetEntity: User::class)]
	#[JoinColumn(name: 'id_user', referencedColumnName: 'id', nullable: true)]
	private User $user;

	public function __construct(
		string $url,
		string $description,
		User $user
	)
	{
		$this->id = Uuid::v4()->toRfc4122();
		$this->url = $url;
		$this->description = $description;
		$this->user = $user;
	}

	public function getId(): string
	{
		return $this->id;
	}

	public function getUrl(): string
	{
		return $this->url;
	}

	public function getUser(): User
	{
		return $this->user;
	}

	public function setUser(User $user): void
	{
		$this->user = $user;
	}

	public function getDescription(): string
	{
		return $this->description;
	}

	public function setUrl(string $url): void
	{
		$this->url = $url;
	}

	public function setDescription(string $description): void
	{
		$this->description = $description;
	}

}
