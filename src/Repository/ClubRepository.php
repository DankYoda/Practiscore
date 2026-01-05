<?php
declare(strict_types=1);

namespace App\Repository;

use App\Model\Entity\Club;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class ClubRepository extends ServiceEntityRepository {
	public function __construct(
		ManagerRegistry $registry,
	)
    {
		parent::__construct($registry, Club::class);
	}

	public function save(Club $entity): void {
		$this->getEntityManager()->persist($entity);
	}

	public function remove(Club $entity, bool $flush = false): void {
		$this->getEntityManager()->remove($entity);
	}
}
