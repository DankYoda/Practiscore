<?php
declare(strict_types=1);

namespace App\Service\State\Processor\User;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Exception\Resource\TokenInvalid;
use App\Model\TokenResource;
use App\Repository\UserRepository;
use App\Service\PasswordSetter;
use App\Service\TokenFactory;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;

readonly class PasswordResetProcessor implements ProcessorInterface
{
	function __construct(
		private PasswordSetter $passwordSetter,
		private EntityManagerInterface $entityManager,
		private RequestStack $requestStack,
		private TokenFactory $tokenFactory,
        private UserRepository $userRepository
	)
	{
	}

	public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): TokenResource {
		$user = $this->userRepository->find($uriVariables['userId']);

        if (!$user)
            throw new UserNotFoundException('User does not exist!');

        $token = $this->readToken();
		$this->passwordSetter->reset($user, $token, $data->newPassword);

		$this->entityManager->persist($user);
		$this->entityManager->flush();
        return $this->tokenFactory->create($user);
	}

    private function readToken(): string {
        $authorization = $this->requestStack->getCurrentRequest()->headers->get('Authorization');

        if (!$authorization) throw new HttpException(Response::HTTP_UNAUTHORIZED);

        $authorization = explode(' ', $authorization);

        if (count($authorization) < 2 || $authorization[0] !== 'Bearer') throw new TokenInvalid();
        return $authorization[1];
    }
}
