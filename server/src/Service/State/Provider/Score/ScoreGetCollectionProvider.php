<?php
declare(strict_types=1);

namespace App\Service\State\Provider\Score;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\Repository\GatheringRepository;
use App\Repository\ScoreRepository;
use App\Repository\UserRepository;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

readonly class ScoreGetCollectionProvider implements ProviderInterface
{
	function __construct(
		private GatheringRepository $gatheringRepository,
        private UserRepository $userRepository,
        private ScoreRepository $scoreRepository,
	)
	{

	}
	public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null
	{
        $gathering = $this->gatheringRepository->find($uriVariables['idGather']);
        $user = $this->userRepository->find($uriVariables['idUser']);
        if (!$gathering || !$user)
            throw new NotFoundHttpException();
        return $this->scoreRepository->findBy(['gathering' => $gathering, 'user' => $user]);
	}
}
