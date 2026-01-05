<?php
declare(strict_types=1);

namespace App\Service\Authentication;

use App\Service\Cookie\CookieToken;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;

readonly class AuthenticationSuccessHandler implements AuthenticationSuccessHandlerInterface
{
	function __construct(
		private CookieToken $cookieToken,
	)
	{
		
	}

	public function onAuthenticationSuccess(Request $request, TokenInterface $token): Response
	{
		return $this->cookieToken->cookieTokenCreate($token);
	}
}
