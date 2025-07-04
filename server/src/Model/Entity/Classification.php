<?php
declare(strict_types=1);

namespace App\Model\Entity;

use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(
	readOnly: true,
)]
#[Get]
#[GetCollection]
class Classification {
	#[ORM\Id]
	#[ORM\Column]
	private string $name;
	#[ORM\ManyToOne(targetEntity: MatchType::class, inversedBy: "classifications")]
	#[ORM\JoinColumn(name: 'id_match_type', referencedColumnName: 'name')]
	private MatchType $matchType;

	public function __construct(
		string $name,
		MatchType $matchType,
	) {
		$this->name = $name;
		$this->matchType = $matchType;
	}

	public function getMatchType(): MatchType
	{
		return $this->matchType;
	}

	public function setMatchType(MatchType $matchType): void
	{
		$this->matchType = $matchType;
	}

	public function getName(): string
	{
		return $this->name;
	}
}