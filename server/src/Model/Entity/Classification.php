<?php
declare(strict_types=1);

namespace App\Model\Entity;

use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Link;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(
	readOnly: true,
)]
#[Get(
	uriTemplate: '/classification/{name}',
	uriVariables: [
		'name' => new Link(fromClass: Classification::class),
	],
)]
class Classification {
	#[ORM\Id]
	#[ORM\Column]
	#[Groups(['match_type:default:read'])]
	private string $name;
	#[ORM\Column]
	#[Groups(['match_type:default:read'])]
	private int $ordinal = 0;
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
	public function getOrdinal(): int
	{
		return $this->ordinal;
	}
	
	public function setOrdinal(int $ordinal): void
	{
		$this->ordinal = $ordinal;
	}
}