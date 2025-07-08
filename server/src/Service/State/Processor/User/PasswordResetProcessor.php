<?php
declare(strict_types=1);

namespace App\Service\State\Processor\User;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Repository\UserRepository;
use App\Service\PasswordSetter;
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
        private UserRepository $userRepository
	)
	{
	}

	public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []) {
		$user = $this->userRepository->find($uriVariables['userId']);

        if (!$user)
            throw new UserNotFoundException('User does not exist!');
		
		//$this->passwordSetter->reset($user, $token, $data->newPassword);

		$this->entityManager->persist($user);
		$this->entityManager->flush();
        return null;
	}
}
