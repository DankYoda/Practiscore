<?php
declare(strict_types=1);

namespace App\Service\State\Provider\Score;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\Model\Entity\Score;
use App\Repository\GatheringRepository;
use App\Repository\ScoreRepository;
use App\Repository\UserRepository;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

readonly class ScorePostProvider implements ProviderInterface
{
	function __construct(
		private GatheringRepository $gatheringRepository,
        private UserRepository $userRepository,
	)
	{

	}
	public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null
	{
        $gathering = $this->gatheringRepository->find($uriVariables['idGather']);
        if (!$gathering) throw new NotFoundHttpException('Gathering does not exist.');
        $user = $this->userRepository->find($uriVariables['idUser']);
        if (!$user) throw new NotFoundHttpException('User not found.');
        return new Score($user, $gathering);

	}
}
