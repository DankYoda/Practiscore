<?php
declare(strict_types=1);

namespace App\Repository;

use App\Model\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bridge\Doctrine\Security\User\UserLoaderInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class UserRepository extends ServiceEntityRepository implements UserLoaderInterface {
	public function __construct(
		ManagerRegistry $registry,
	) {
		parent::__construct($registry, User::class);
	}

	public function save(User $entity): void {
		$this->getEntityManager()->persist($entity);
	}

	public function remove(User $entity, bool $flush = false): void {
		$this->getEntityManager()->remove($entity);
	}

	public function loadUserByIdentifier(string $identifier): ?UserInterface {
		$entityManager = $this->getEntityManager();

		return $entityManager->createQuery(
			'SELECT u
                FROM App\Model\Entity\User u
                WHERE u.id = :query'
		)
			->setParameter('query', $identifier)
			->getOneOrNullResult();
	}
	
	public function flush(): void {
		$this->getEntityManager()->flush();
	}
}
