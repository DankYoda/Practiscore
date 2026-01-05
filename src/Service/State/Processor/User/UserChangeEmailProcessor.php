<?php
declare(strict_types=1);

namespace App\Service\State\Processor\User;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use ApiPlatform\Symfony\Security\Exception\AccessDeniedException;
use App\Repository\UserRepository;
use App\Service\EmailVerifier;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

readonly class UserChangeEmailProcessor implements ProcessorInterface
{
	function __construct(
		private EmailVerifier          $emailVerifier,
		private EntityManagerInterface $entityManager,
        private UserRepository         $userRepository
	)
	{
	}

	public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = [])
	{
        $user = $this->userRepository->find($uriVariables['userId']);
		if (!$user->getPassword())
			throw new AccessDeniedException();
		if ($this->userRepository->findBy(['email'=>$data->email]))
			throw new UnprocessableEntityHttpException('That email is already used');
		$user->setEmailVerified(false);
        $user->setEmail($data->email);
		$this->entityManager->persist($user);
		$this->entityManager->flush();
		$this->emailVerifier->sendEmail($user);
		return $user;
	}
}
