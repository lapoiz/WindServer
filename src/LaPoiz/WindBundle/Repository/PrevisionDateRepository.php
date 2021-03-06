<?php

namespace LaPoiz\WindBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * PrevisionDateRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class PrevisionDateRepository extends EntityRepository
{
	// date on Y-m-d format
	// return previsionDate from id of DataWindPrev and a date
	public  function getFromOneWebSiteForOneDay($dataWindPrevId,$date) {	
		$queryBuilder = $this->createQueryBuilder('previsionDate');
		
		if ($dataWindPrevId!=null) {
			$queryBuilder
			->leftJoin('previsionDate.dataWindPrev', 'dataWindPrev')
			->where("dataWindPrev.id=".$dataWindPrevId." and previsionDate.datePrev='".$date."'")
			->orderBy('previsionDate.created','DESC');
		} else {
			$queryBuilder->orderBy('previsionDate.created')
			->setMaxResults(1);
		}
		try {
			return $queryBuilder->getQuery()->getResult();
		} catch (\Doctrine\ORM\NoResultException $e) {
			return null;
		}
		return $query->getResult();
	}
	
	// date on Y-m-d format
	public  function getFromOneWebSiteFromAnalyseDateOneDay($dataWindPrevId,$date) {
		$queryBuilder = $this->createQueryBuilder('previsionDate');

		if ($dataWindPrevId!=null) {
			$queryBuilder
			->leftJoin('previsionDate.dataWindPrev', 'dataWindPrev')
			->where("dataWindPrev.id=".$dataWindPrevId." and previsionDate.created>='".$date.
					"' and  previsionDate.created<='".date('Y-m-d',strtotime("+1 day",strtotime($date)))."'")
			->orderBy('previsionDate.datePrev','DESC');
		} else {
			$queryBuilder->orderBy('previsionDate.created')
			->setMaxResults(1);
		}
		try {
			return $queryBuilder->getQuery()->getResult();
		} catch (\Doctrine\ORM\NoResultException $e) {
			return null;
		}
		return $queryBuilder->getResult();
	}
	
	// date on Y-m-d format
	public function getFromOneWebSiteForOneMonth($dataWindPrevId,$date) {
		$queryBuilder = $this->createQueryBuilder('previsionDate');

		if ($dataWindPrevId!=null) {
			$queryBuilder
			->leftJoin('previsionDate.dataWindPrev', 'dataWindPrev')
			->where("dataWindPrev.id=".$dataWindPrevId." and previsionDate.created<='".date('Y-m-d',strtotime("+1 day",strtotime($date))).
					"' and  previsionDate.created>='".date('Y-m-d',strtotime("-1 month",strtotime($date)))."'")
			->orderBy('previsionDate.created , previsionDate.datePrev');
		} else {
			$queryBuilder->orderBy('previsionDate.created')
			->setMaxResults(1);
		}
		try {
			return $queryBuilder->getQuery()->getResult();
		} catch (\Doctrine\ORM\NoResultException $e) {
			return null;
		}
		return $query->getResult();
	}
	
	public function getTheLastCreated($dataWindPrevId=null)
	{
		$queryBuilder = $this->createQueryBuilder('previsionDate');
		if ($dataWindPrevId!=null) {
			$queryBuilder
			->leftJoin('previsionDate.dataWindPrev', 'dataWindPrev')
			->where($queryBuilder->expr()->eq('dataWindPrev.id',$dataWindPrevId))
			->orderBy('previsionDate.created','DESC')
			->setMaxResults(1);
		} else {
			$queryBuilder->orderBy('previsionDate.created')
			->setMaxResults(1);
		}
		try {
			return $queryBuilder->getQuery()->getSingleResult();
		} catch (\Doctrine\ORM\NoResultException $e) {
			return null;
		}
	}


	/*
	 * Return list PrevisionDate of the last webSite scan
	 */
	public function getLastCreated($dataWindPrev)
	{
		$theLastPrevisionDate=$this->getTheLastCreated($dataWindPrev->getId());
		$queryBuilder = $this->createQueryBuilder('previsionDate');
		$queryBuilder
			->leftJoin('previsionDate.dataWindPrev', 'dataWindPrev')
			->where($queryBuilder->expr()->andx(
				$queryBuilder->expr()->eq('dataWindPrev.id',$dataWindPrev->getId()),
				$queryBuilder->expr()->gte('previsionDate.created',"'".$theLastPrevisionDate->getCreated()->format('Y-m-d H:i:s')."'")))
			->orderBy('previsionDate.created');

		try {
			return $queryBuilder->getQuery()->getResult();
		} catch (\Doctrine\ORM\NoResultException $e) {
			return null;
		}
	}

	public  function findLastCreated($previsionDate) {

		$queryStr = 'SELECT previsionDate FROM LaPoizWindBundle:PrevisionDate AS previsionDate ';
		$queryStr = ' LEFT JOIN previsionDate.dataWindPrev as dataWindPrev on dataWindPrev.id=previsionDate.dataWindPrev_id';
		$queryStr .= ' WHERE dataWindPrev.id = :dataWindPrevId ';
		//$queryStr .= ' previsionDate.created >= :dateCreated ';
		//$queryStr .= ' order by previsionDate.created';
		//$params = array();
		//$params['dateCreated'] = $previsionDate->getCreated()->format('Y-m-d');
		$params['dataWindPrevId'] = $previsionDate->getDataWindPrev()->getId();

		$query=$this->getEntityManager()->createQuery($queryStr);
		$query->setParameters($params);

		return $query->getResult();
	}

	/**
	 * Return list of PrevisionDate for next days (after now) for the spot and website of DataWindPrev
	 * @param DataWindPrev $dataWindPrev
	 */
	public function getPrevDateOneWebSiteNextDays($dataWindPrev)
	{
		$queryBuilder = $this->createQueryBuilder('previsionDate');
		$queryBuilder
		->where($queryBuilder->expr()->andx(
		$queryBuilder->expr().eq('previsionDate.dataWindPrev_id',$dataWindPrev.id),
		$queryBuilder->expr().gte('previsionDate.datePrev','NOW')));
		try {
			return $queryBuilder->getQuery()->getResult();
		} catch (\Doctrine\ORM\NoResultException $e) {
			return null;
		}
	}

	/**
	 * Return list of PrevisionDate for next days (after now) for the spot for all website
	 * @param Spot $spot
	 */
	public function getPrevDateAllWebSiteNextDays($spot)
	{
		$queryBuilder = $this->createQueryBuilder('previsionDate');
		$queryBuilder
		->where($queryBuilder->expr()->andx(
		$queryBuilder->expr().eq('previsionDate.dataWindPrev.spot_id',$spot.id),
		$queryBuilder->expr().gte('previsionDate.datePrev','NOW')));
		try {
			return $queryBuilder->getQuery()->getResult();
		} catch (\Doctrine\ORM\NoResultException $e) {
			return null;
		}
	}

	/**
	 * Return list of PrevisionDate for one day (date) for the spot for all website
	 * @param Spot $spot
	 * @param DateTime $date
	 */
	public function getPrevDateAllWebSiteOneDay($spot,$date)
	{
		$queryBuilder = $this->createQueryBuilder('previsionDate');
		$queryBuilder
		->where($queryBuilder->expr()->andx(
		$queryBuilder->expr().eq('previsionDate.dataWindPrev.spot_id',$spot.id),
		$queryBuilder->expr().eq('previsionDate.datePrev',$date)));//TODO:check for houre
		try {
			return $queryBuilder->getQuery()->getResult();
		} catch (\Doctrine\ORM\NoResultException $e) {
			return null;
		}
	}
}