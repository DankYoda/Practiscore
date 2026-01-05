<?php
declare(strict_types=1);

namespace App\Model\Entity;

use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Link;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(
	readOnly: true,
)]
#[ORM\Table(name: 'match_type')]
#[Get(
	uriTemplate: '/match_type/{name}',
	uriVariables: [
		'name' => new Link(fromClass: MatchType::class),
	],
	normalizationContext: ['groups' => ['match_type:default:read']],
)]
#[GetCollection(
	uriTemplate: '/match_type',
	normalizationContext: ['groups' => ['match_type:default:read']],
)]
class MatchType {
	#[ORM\Id]
	#[ORM\Column]
	#[Groups(['match_type:default:read'])]
	private string $name;
	#[ORM\Column]
	#[Groups(['match_type:default:read'])]
	private int $category;

	#[ORM\OneToMany(targetEntity: Division::class, mappedBy: 'matchType')]
	#[Groups(['match_type:default:read'])]
	private Collection $divisions;

	#[ORM\OneToMany(targetEntity: Classification::class, mappedBy: 'matchType')]
	#[Groups(['match_type:default:read'])]
	private Collection $classifications;

	public function __construct(
		string $name,
	)
	{
		$this->name = $name;
		$this->divisions = new ArrayCollection();
		$this->classifications = new ArrayCollection();
	}

	public function getName(): string
	{
		return $this->name;
	}

	public function getCategory(): int
	{
		return $this->category;
	}

	public function setCategory(int $category): void
	{
		$this->category = $category;
	}

	public function getDivisions(): Collection
	{
		return $this->divisions;
	}

	public function setDivisions(Collection $divisions): void
	{
		$this->divisions = $divisions;
	}

	public function getClassifications(): Collection
	{
		return $this->classifications;
	}

	public function setClassifications(Collection $classifications): void
	{
		$this->classifications = $classifications;
	}


}