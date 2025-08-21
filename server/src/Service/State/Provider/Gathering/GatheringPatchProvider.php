<?php

namespace App\Service\State\Provider\Gathering;

use ApiPlatform\Metadata\Exception\AccessDeniedException;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\Model\Entity\Gathering;
use App\Repository\ClubRepository;
use App\Repository\GatheringRepository;
use Symfony\Bundle\SecurityBundle\Security;

class GatheringPatchProvider implements ProviderInterface
{
	function __construct(
		private readonly Security $security,
		private readonly ClubRepository $clubRepository,
		private readonly GatheringRepository $gatheringRepository,
	)
	{
	
	}
	public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null
	{
		$club = $this->clubRepository->find($uriVariables['idClub']);
		if (!$club || ($club !== $this->security->getUser()->getManagedClub()))
			return new AccessDeniedException();
		return $this->gatheringRepository->findOneBy(['id' => $uriVariables['id'], 'club' => $club]);
	}
}