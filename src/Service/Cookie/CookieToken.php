<?php
declare(strict_types=1);

namespace App\Service\Cookie;

use App\Repository\UserRepository;
use DateInterval;
use ParagonIE\Paseto\Builder;
use ParagonIE\Paseto\Keys\SymmetricKey;
use ParagonIE\Paseto\Protocol\Version3;
use ParagonIE\Paseto\Purpose;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use UnexpectedValueException;

readonly class CookieToken
{
	function __construct(
		private RequestStack $requestStack,
		private UserRepository $userRepository,
		private string $password,
		private string $appIdentifier,
	)
	{
		//if keyPassphrase < 32, throw exception
		if (strlen($this->password)<32)
			throw new UnexpectedValueException("Token secret must be at least 32 characters.");
	}
	function cookieTokenCreate(TokenInterface $token): Response
	{
		$user = $this->userRepository->findOneBy(['username'=>$token->getUserIdentifier()]);
		$privateKey = new SymmetricKey(hash('sha256', $this->password, true), new Version3());
		$now = new \DateTimeImmutable();
		$expires = (new \DateTimeImmutable())->add(new DateInterval('P01D'));
		$token = (new Builder())
			->setKey($privateKey)
			->setVersion(new Version3())
			->setPurpose(Purpose::local())
			->setClaims(['id'=>$user->getId()])
			->setExpiration($expires)
			->setIssuedAt($now)
			->setNotBefore($now);
		$cookie = Cookie::create($this->appIdentifier)
			->withValue($token->toString())
			->withHttpOnly(true)
			//lax set for all requests on localhost
			->withSameSite('lax')
			//localhost is exempt from https
			->withSecure(true)
			->withExpires($expires)
			->withPath($this->requestStack->getCurrentRequest()->getBasePath());
		$response = new Response();
		$response->setStatusCode(204);
		$response->headers->setCookie($cookie);
		return $response;
	}
	
	function cookieTokenDelete(): Response
	{
		$response = new Response(status: 204);
		$response->headers->clearCookie(
			$this->appIdentifier,
			$this->requestStack->getCurrentRequest()->getBasePath(),
			null,
			true,
			true,
			'lax',
		);
		return $response;
	}
}
