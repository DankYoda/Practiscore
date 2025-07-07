<?php
declare(strict_types=1);

namespace App\Service\State\Processor\User;

use ApiPlatform\Exception\InvalidValueException;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Clock\ClockInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;

class ChangePasswordProcessor implements ProcessorInterface
{
	function __construct(
		private readonly UserRepository $userRepository,
		private readonly UserPasswordHasherInterface $passwordHasher,
		private readonly EntityManagerInterface $entityManager,
		private readonly ClockInterface $clock,
	)
	{
	}

	public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = [])
	{
		$user = $this->userRepository->find($uriVariables['userId']);
		if (!$user)
			throw new UserNotFoundException();
		if (!$this->passwordHasher->isPasswordValid($user, $data->oldPassword))
			throw new InvalidValueException("Old Password doesn't match");
		if($this->passwordHasher->isPasswordValid($user, $data->newPassword))
			throw new InvalidValueException('New Password cannot be old password');
		$user->setPassword($this->passwordHasher->hashPassword($user, $data->newPassword));
		$user->setPasswordChanged($this->clock->now());
		$this->entityManager->persist($user);
		$this->entityManager->flush();
		return $user;
	}
}
