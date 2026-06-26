<?php
declare(strict_types=1);

namespace App\Service\Authentication;

use App\Repository\UserRepository;
use ParagonIE\Paseto\Exception\PasetoException;
use ParagonIE\Paseto\Exception\RuleViolation;
use ParagonIE\Paseto\Keys\SymmetricKey;
use ParagonIE\Paseto\Parser;
use ParagonIE\Paseto\Protocol\Version3;
use ParagonIE\Paseto\ProtocolCollection;
use ParagonIE\Paseto\Purpose;
use ParagonIE\Paseto\Rules\ValidAt;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Http\AccessToken\AccessTokenHandlerInterface;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;

readonly class TokenHandler implements AccessTokenHandlerInterface
{
	function __construct(
		private string         $password,
		private UserRepository $userRepository,
	)
	{

	}
	public function getUserBadgeFrom(#[\SensitiveParameter] string $accessToken): UserBadge
	{
		$parser = (new Parser())
			->setKey(new SymmetricKey(hash('sha256', $this->password, true), new Version3()))
			->setPurpose(Purpose::local())
			->setAllowedVersions(ProtocolCollection::v3())
			->addRule(new ValidAt());
		try {
			$token = $parser->parse($accessToken);
		} catch (PasetoException $ex) {
			//determine what error type is
			if ($ex instanceof RuleViolation&&$ex->getMessage()==="This token has expired.")
				throw new CustomUserMessageAuthenticationException('API token is expired');
			
			throw new CustomUserMessageAuthenticationException("The Token is invalid");
		}
		//check issued before
		$user = $this->userRepository->find($token->get('id'));
		if (!$user)
			throw new CustomUserMessageAuthenticationException("The Token is invalid");
		if (($user->getNotBefore()!=null)&&($token->getIssuedAt() < $user->getNotBefore()))
		{
			throw new CustomUserMessageAuthenticationException('API token is expired');
		}
		return new UserBadge($token->get('id'));
	}
}
