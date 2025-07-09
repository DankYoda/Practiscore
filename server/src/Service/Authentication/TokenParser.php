<?php
declare(strict_types=1);

namespace App\Service\Security;

use App\Exception\Resource\TokenExpired;
use ParagonIE\Paseto\Exception\PasetoException;
use ParagonIE\Paseto\Exception\RuleViolation;
use ParagonIE\Paseto\JsonToken;
use ParagonIE\Paseto\Keys\SymmetricKey;
use ParagonIE\Paseto\Parser;
use ParagonIE\Paseto\Protocol\Version3;
use ParagonIE\Paseto\ProtocolCollection;
use ParagonIE\Paseto\Purpose;
use ParagonIE\Paseto\Rules\ValidAt;
use Symfony\Component\Clock\ClockInterface;
use Symfony\Component\Clock\NativeClock;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use UnexpectedValueException;

readonly class TokenParser
{
	function __construct(
		private string $password,
        #[Autowire(service: NativeClock::class)] private ClockInterface $clock
	)
	{
		if (strlen($this->password)<32)
			throw new UnexpectedValueException("Token secret must be at least 32 characters.");
	}

    public function parse(string $accessToken): JsonToken {
        $parser = (new Parser())
            ->setKey(new SymmetricKey(hash('sha256', $this->password, true), new Version3()))
            ->setPurpose(Purpose::local())
            ->setAllowedVersions(ProtocolCollection::v3())
            ->addRule(new ValidAt($this->clock->now()));
        try {
            return $parser->parse($accessToken);
        } catch (PasetoException $ex) {
            //determine what error type is
            if ($ex instanceof RuleViolation && $ex->getMessage() === "This token has expired.")
                throw new TokenExpired();

            throw $ex;
        }
    }
}
