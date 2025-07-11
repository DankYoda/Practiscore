<?php
declare(strict_types=1);

namespace App\Service\Authentication;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Authentication\AuthenticationFailureHandlerInterface;

class AuthenticationFailureHandler implements AuthenticationFailureHandlerInterface
{
	public function onAuthenticationFailure(Request $request, AuthenticationException $exception): Response
	{
		return new JsonResponse(['Message' => 'Authentication Failure'], Response::HTTP_UNPROCESSABLE_ENTITY);
	}
}
