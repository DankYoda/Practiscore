<?php
declare(strict_types=1);

namespace App\Model\Entity;

use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(
	readOnly: true,
)]
#[ORM\Table(name: 'match_type')]
#[Get]
#[GetCollection]
class MatchType {
	#[ORM\Id]
	#[ORM\Column]
	private string $name;
	#[ORM\Column]
	private int $category;

	#[ORM\OneToMany(targetEntity: Division::class, mappedBy: 'matchType')]
	private Collection $divisions;

	#[ORM\OneToMany(targetEntity: Classification::class, mappedBy: 'matchType')]
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