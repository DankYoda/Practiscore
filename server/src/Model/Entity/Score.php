<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

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

	public function __construct(
        string $username,
        string $email,
        ?\DateTimeImmutable $passwordChanged = null,
    )
    {
        $this->id = Uuid::v4()->toRfc4122();
    }

	public function getId(): string
	{
		return $this->id;
	}
}