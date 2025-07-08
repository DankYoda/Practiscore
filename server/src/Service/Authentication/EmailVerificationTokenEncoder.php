<?php

namespace App\Service\Authentication;

readonly class ResetPasswordTokenEncoder
{
	function __construct(
		private string $password,
		private UserRepository $userRepository,
        private LoggerInterface $logger,
        private TokenParser $tokenParser,
        private ClockInterface $clock
	)
	{
        if (strlen($this->password)<32)
            throw new UnexpectedValueException("Token secret must be at least 32 characters.");
	}

    public function verify(string $accessToken): User {
        try {
            /** @noinspection DuplicatedCode */
            $parsedToken = $this->tokenParser->parse($accessToken);

            $user = $parsedToken->get('user') ? $this->userRepository->find($parsedToken->get('user')) : null;

            // If the user does not exist
            if (!$user) {
                $this->logger->info('An unexpected error occurred: A token was decoded with an user that does not exist.');
                throw new TokenInvalid();
            }

            // If the token issued date is before the user's from date
            if ($user->getNotBefore() && ($parsedToken->getIssuedAt() < $user->getNotBefore())) {
                throw new TokenExpired();
            }

            if ($parsedToken->get('type') !== 'password')
                throw new TokenInvalid();
        } catch (PasetoException) {
            throw new TokenInvalid();
        }

        return $user;
	}

    public function create(User $user): string
    {
        $privateKey = new SymmetricKey(hash('sha256', $this->password, true), new Version3());
        return (new Builder())
            ->setKey($privateKey)
            ->setVersion(new Version3())
            ->setPurpose(Purpose::local())
            ->setClaims([
                'type'=>'password',
                'user'=>$user->getId()
            ])
            ->setNotBefore($this->clock->now())
            ->setExpiration($this->clock->now()->add(new DateInterval('PT10M')))
            ->setIssuedAt($this->clock->now())
            ->toString();
    }
}
