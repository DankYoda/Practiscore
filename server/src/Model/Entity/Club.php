<?php
declare(strict_types=1);

namespace App\Model\Entity;

use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Link;
use ApiPlatform\Metadata\Patch;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity]
#[Get(
	uriTemplate: '/club/{id}',
	uriVariables: [
		'id' => new Link(fromClass: Club::class),
	],
)]
#[GetCollection(
	uriTemplate: '/club',
)]
#[Patch(
	uriTemplate: '/club/{id}',
	uriVariables: [
		'id' => new Link(fromClass: Club::class),
	],
)]
class Club{
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
	private string $name;
	#[ORM\Column(
		unique: true,
	)]
	#[Assert\Email]
	#[Groups(['user:default:read'])]
	private string $email;
	#[ORM\Column]
	#[Groups(['user:default:read'])]
	private string $address;
	#[ORM\Column]
	#[Groups(['user:default:read'])]
	private string $socials = '';
	#[ORM\Column]
	#[Groups(['user:default:read'])]
	private string $website = '';
	
	public function __construct(
		string $name,
		string $email,
		string $address,
	){
		$this->id = Uuid::v4()->toRfc4122();
		$this->name = $name;
		$this->email = $email;
		$this->address = $address;
	}
	
	public function getId(): string
	{
		return $this->id;
	}
	
	public function getEmail(): string
	{
		return $this->email;
	}
	
	public function getName(): string
	{
		return $this->name;
	}
	
	public function setName(string $name): void
	{
		$this->name = $name;
	}
	
	public function setEmail(string $email): void
	{
		$this->email = $email;
	}
	
	public function getAddress(): string
	{
		return $this->address;
	}
	
	public function setAddress(string $address): void
	{
		$this->address = $address;
	}
	
	public function getSocials(): string
	{
		return $this->socials;
	}
	
	public function setSocials(string $socials): void
	{
		$this->socials = $socials;
	}
	
	public function getWebsite(): string
	{
		return $this->website;
	}
	
	public function setWebsite(string $website): void
	{
		$this->website = $website;
	}
}