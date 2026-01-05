<?php
declare(strict_types=1);

namespace App\Service\State\Provider\User;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\Repository\UserRepository;

readonly class SendPasswordResetProvider implements ProviderInterface {

    public function __construct(private UserRepository $userRepository) {
    }

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null {
        return $this->userRepository->findOneBy(['email' => $uriVariables['email']]);
    }
}
