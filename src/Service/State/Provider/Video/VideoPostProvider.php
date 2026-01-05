<?php
declare(strict_types=1);

namespace App\Service\State\Provider\Video;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\Model\Entity\Video;
use Symfony\Bundle\SecurityBundle\Security;

readonly class VideoPostProvider implements ProviderInterface
{
	function __construct(
		private Security $security,
	)
	{

	}
	public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null
	{
		return new Video('', '', $this->security->getUser());
	}
}
