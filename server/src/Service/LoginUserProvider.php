<?php
declare(strict_types=1);

namespace App\Service;

use App\Model\Entity\User;
use App\Repository\UserRepository;
use Exception;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

readonly class LoginUserProvider implements UserProviderInterface
{
	function __construct(
		private UserRepository $userRepository,
	)
	{
	
	}

    /**
     * @throws Exception
     */
    public function refreshUser(UserInterface $user): UserInterface
	{
		if (!$user instanceof User) {
			throw new UnsupportedUserException(sprintf('Invalid user class "%s".', $user::class));
		}
		
		// Return a User object after making sure its data is "fresh".
		// Or throw a UserNotFoundException if the user no longer exists.
		throw new Exception('TODO: fill in refreshUser() inside '.__FILE__);
	}
	
	public function supportsClass(string $class): bool
	{
		return User::class === $class || is_subclass_of($class, User::class);
	}
	
	public function loadUserByIdentifier(string $identifier): UserInterface
	{
		$user = $this->userRepository->findOneBy(['username' => $identifier]);
		if (!$user)
		{
			throw new UserNotFoundException();
		}
		return $user;
	}
}