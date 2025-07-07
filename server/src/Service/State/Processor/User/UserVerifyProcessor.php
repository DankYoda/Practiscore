<?php
declare(strict_types=1);

namespace App\Service\State\Processor\User;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Exception\Resource\TokenInvalid;
use App\Service\EmailVerifier;
use App\Service\TokenFactory;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

readonly class UserVerifyProcessor implements ProcessorInterface
{
	public function __construct(
		private EntityManagerInterface $entityManager,
		private RequestStack $requestStack,
		private EmailVerifier $emailVerifier,
        private TokenFactory $tokenFactory
	) {}

	public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []) {
        $this->emailVerifier->verify($data, $this->readToken());
        $this->entityManager->persist($data);
        $this->entityManager->flush();
        return $this->tokenFactory->create($data);
	}

    private function readToken(): string {
        $authorization = $this->requestStack->getCurrentRequest()->headers->get('Authorization');

        if (!$authorization) throw new HttpException(Response::HTTP_UNAUTHORIZED);

        $authorization = explode(' ', $authorization);

        if (count($authorization) < 2 || $authorization[0] !== 'Bearer') throw new TokenInvalid();
        return $authorization[1];
    }
}
