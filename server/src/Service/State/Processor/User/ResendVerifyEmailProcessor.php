<?php
declare(strict_types=1);

namespace App\Service\State\Processor\User;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Repository\UserRepository;
use App\Service\EmailVerifier;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;

class ResendVerifyEmailProcessor implements ProcessorInterface
{
	function __construct(
		private readonly UserRepository $userRepository,
		private readonly EmailVerifier $emailVerifier,
	)
	{

	}

	public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = [])
	{
		$user = $this->userRepository->find($uriVariables['userId']);

		if (!$user)
			throw new UserNotFoundException('User does not exist!');

		if(!$user->isEmailVerified())
			$this->emailVerifier->sendEmail($user);
	}
}
