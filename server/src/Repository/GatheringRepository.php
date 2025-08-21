<?php
declare(strict_types=1);

namespace App\Repository;

use App\Model\Entity\Gathering;
use App\Model\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class GatheringRepository extends ServiceEntityRepository {
	public function __construct(
		ManagerRegistry $registry,
	) {
		parent::__construct($registry, Gathering::class);
	}

	public function save(User $entity): void {
		$this->getEntityManager()->persist($entity);
	}

	public function remove(User $entity, bool $flush = false): void {
		$this->getEntityManager()->remove($entity);
	}
}
