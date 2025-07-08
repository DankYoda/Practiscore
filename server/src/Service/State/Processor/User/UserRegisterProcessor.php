<?php
declare(strict_types=1);

namespace App\Service\State\Processor\User;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use ApiPlatform\Validator\ValidatorInterface;
use App\Repository\UserRepository;
use App\Service\EmailVerifier;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

readonly class UserRegisterProcessor implements ProcessorInterface
{
	public function __construct(
		private EmailVerifier $emailVerifier,
		private EntityManagerInterface $entityManager,
		private UserRepository $userRepository,
		private UserPasswordHasherInterface $passwordHasher,
        private ValidatorInterface $validator,
	)
	{

	}
	public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = [])
	{
		$user = $data->user;
		if ($this->userRepository->findBy(['email'=>$user->getEmail()]))
			throw new Exception('A user exists with that email.');
		if ($this->userRepository->findBy(['username'=>$user->getUsername()]))
			throw new Exception('A user exists with that username.');

		$user->setPassword($this->passwordHasher->hashPassword($user, $data->plainPassword));
        $this->validator->validate($user, $operation->getValidationContext() ?? []);
		$this->entityManager->persist($user);
		$this->entityManager->flush();
		$this->emailVerifier->sendEmail($user);
        return null;
	}
}
