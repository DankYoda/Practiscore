<?php

namespace App\Service\State\Provider\Gathering;

use ApiPlatform\Metadata\Exception\AccessDeniedException;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\Model\Entity\Gathering;
use App\Repository\ClubRepository;
use Symfony\Bundle\SecurityBundle\Security;

class GatheringPostProvider implements ProviderInterface
{
	function __construct(
		private readonly Security $security,
		private readonly ClubRepository $clubRepository,
	)
	{
	
	}
	public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null
	{
		$club = $this->clubRepository->find($uriVariables['idClub']);
		if ($club && ($club === $this->security->getUser()->getManagedClub()))
			return new Gathering($club);
		return new AccessDeniedException();
	}
}