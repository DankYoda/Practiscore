<?php
declare(strict_types=1);

namespace App\Service\Authentication;


use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\AccessToken\AccessTokenExtractorInterface;

readonly class TokenExtractor implements AccessTokenExtractorInterface
{
	function __construct(
		private string $appIdentifier,
	)
	{
		
	}
	public function extractAccessToken(Request $request): ?string
	{
		return $request->cookies->get($this->appIdentifier);
	}
}
