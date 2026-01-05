<?php
declare(strict_types=1);

namespace App\Repository;

use App\Model\Entity\Registration;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class

RegistrationRepository extends ServiceEntityRepository {
	public function __construct(
		ManagerRegistry $registry,
	) {
		parent::__construct($registry, Registration::class);
	}

	public function save(Registration $entity): void {
		$this->getEntityManager()->persist($entity);
	}

	public function remove(Registration $entity, bool $flush = false): void {
		$this->getEntityManager()->remove($entity);
	}
}
