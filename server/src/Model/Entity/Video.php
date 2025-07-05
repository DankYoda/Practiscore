<?php
declare(strict_types=1);

namespace App\Model\Entity;

use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Link;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\OneToOne;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity]
#[Get(
	uriTemplate: '/video/{id}',
	uriVariables: [
		'id' => new Link(fromClass: Video::class),
	],
)]
#[GetCollection(
	uriTemplate: '/video',
)]
#[Post(
	uriTemplate: '/video',
)]
#[Patch(
	uriTemplate: '/video/{id}',
	uriVariables: [
		'id' => new Link(fromClass: Video::class),
	],
)]
#[Delete(
	uriTemplate: '/video/{id}',
	uriVariables: [
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
	private string $name;
	#[ORM\Column]
	private string $data;
	#[OneToOne(targetEntity: User::class)]
	#[JoinColumn(name: 'id_user', referencedColumnName: 'id', nullable: true)]
	private User $user;
	
	public function __construct(
		string $id,
		string $name,
		string $data,
		User $user
	)
	{
		$this->id = Uuid::v4()->toRfc4122();
		$this->name = $name;
		$this->data = $data;
		$this->user = $user;
	}
	
	public function getId(): string
	{
		return $this->id;
	}
	
	public function getName(): string
	{
		return $this->name;
	}
	
	public function setName(string $name): void
	{
		$this->name = $name;
	}
	
	public function getData(): string
	{
		return $this->data;
	}
	
	public function setData(string $data): void
	{
		$this->data = $data;
	}
	
	public function getUser(): User
	{
		return $this->user;
	}
	
	public function setUser(User $user): void
	{
		$this->user = $user;
	}
}