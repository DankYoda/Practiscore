<?php
declare(strict_types=1);

namespace App\Service\State\Processor\User;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Repository\UserRepository;
use App\Service\PasswordSetter;

readonly class SendResetPasswordProcessor implements ProcessorInterface
{
	public function __construct(
		private UserRepository $userRepository,
        private PasswordSetter $passwordSetter
	) {}

	public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): void {
        if (!$data) return;
		$this->passwordSetter->sendEmail($data);
	}
}
