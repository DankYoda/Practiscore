<?php
declare(strict_types=1);

namespace App\Service\Authentication;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;

class AuthenticationSuccessHandler implements AuthenticationSuccessHandlerInterface
{
	
	public function onAuthenticationSuccess(Request $request, TokenInterface $token): ?Response
	{
		// TODO: Implement onAuthenticationSuccess() method.
	}
}