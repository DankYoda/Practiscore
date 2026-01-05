<?php
declare(strict_types=1);

namespace App\Repository;

use App\Model\Entity\Gathering;
use App\Model\Entity\Score;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class ScoreRepository extends ServiceEntityRepository {
	public function __construct(
		ManagerRegistry $registry,
	)
    {
		parent::__construct($registry, Score::class);
	}

	public function save(Score $entity): void {
		$this->getEntityManager()->persist($entity);
	}

	public function remove(Score $entity, bool $flush = false): void {
		$this->getEntityManager()->remove($entity);
	}
}
