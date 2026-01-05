<?php

namespace App\Service\State\Provider\Registration;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\Model\Entity\Registration;
use App\Repository\GatheringRepository;
use App\Repository\RegistrationRepository;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

readonly class RegistrationPostProvider implements ProviderInterface
{
    function __construct(
        private Security $security,
        private GatheringRepository $gatheringRepository,
        private RegistrationRepository $registrationRepository,
    )
    {

    }
    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null
    {
        $gathering = $this->gatheringRepository->find($uriVariables['idGather']);
        if(!$gathering)
            throw new NotFoundHttpException("Gathering not found");
        if ($this->registrationRepository->findOneBy([
            'gathering' => $gathering,
            'user' => $this->security->getUser()
        ]))
            throw new ConflictHttpException("Already registered for event");
        return new Registration($this->security->getUser(), $gathering);
    }
}