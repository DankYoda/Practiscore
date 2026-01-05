<?php

namespace App\Service\State\Provider\Gathering;

use ApiPlatform\Metadata\Exception\AccessDeniedException;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\Repository\ClubRepository;
use App\Repository\GatheringRepository;
use Symfony\Bundle\SecurityBundle\Security;

readonly class GatheringPatchProvider implements ProviderInterface
{
	function __construct(
		private Security            $security,
		private ClubRepository      $clubRepository,
		private GatheringRepository $gatheringRepository,
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