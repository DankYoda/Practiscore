<?php
declare(strict_types=1);

namespace App\Controller;

use App\Service\Cookie\CookieToken;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Annotation\Route;

#[AsController]
class SecurityController
{
	function __construct(
		private readonly CookieToken $cookieToken
	)
	{
	}
	#[Route('/login', name: 'login')]
	public function index(): Response
	{
		return new JsonResponse([
			'message' => 'Login Successful',
		]);
	}
	#[Route('/logout', name: 'app_logout', methods: ['POST'])]
	public function logout(): Response
	{
		return $this->cookieToken->cookieTokenDelete();
	}
}
