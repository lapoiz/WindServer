<?php

namespace LaPoiz\WindBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * SpotRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class SpotRepository extends EntityRepository
{
	
	public function findFirst()
	{
		$queryBuilder = $this->createQueryBuilder('spot');
		$queryBuilder->where($queryBuilder->expr()
			->like('spot.nom',"'%Aubin%'"))
			->setMaxResults(1);;
		try {
			return $queryBuilder->getQuery()->getSingleResult();
		} catch (\Doctrine\ORM\NoResultException $e) {
			return null;
		}
	}
}