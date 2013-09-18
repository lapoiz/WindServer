<?php
namespace LaPoiz\WindBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
//use Symfony\Component\HttpFoundation\Response;	

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use LaPoiz\WindBundle\Entity\Spot;
use LaPoiz\WindBundle\Entity\DataWindPrev;
use LaPoiz\WindBundle\core\graph\TransformeToHighchartsDataTabForJson;
use LaPoiz\WindBundle\core\graph\SpotJsonObject;

class JsonSpotController extends Controller

{	
	
	/**
	 * @Template()
	*/
	public function listAction()
	{
		$em = $this->container->get('doctrine.orm.entity_manager');
		$spots = $em->getRepository('LaPoizWindBundle:Spot')->findAll();

		$listSpot= array();
		foreach ($spots as $key => $spot) {
			$data= array('name' => $spot->getNom(), "id" => $spot->getId());
			$listSpot[]=$data;
		}

		return new JsonResponse(array(
				'success' => true,
				'list' => $listSpot));
	}


	/**
	 * @Template()
	*/
	public function getAction($id=null)
	{
		
		$message='';
		$em = $this->container->get('doctrine.orm.entity_manager');
		if (isset($id) && $id!=-1)
		{
			$spot = $em->find('LaPoizWindBundle:Spot', $id);
			if (!$spot)
			{
				return new JsonResponse(array(
					'success' => false,
					'description' => "No spot find in GetAction"
				));
			}
			// Normal way, we find spot
					
			// get all website for this spot 
			//$dataWindPrevList = $this->getDoctrine()->getRepository('LaPoizWindBundle:DataWindPrev')->getFromSpot($spot);
			
			$tabJson = TransformeToHighchartsDataTabForJson::createResultJson($spot);
			//$dataArray = array();
			 $tabJson->series=array();
			foreach ($spot->getDataWindPrev() as $dataWindPrev) {
				$previsionDateList = $this->getDoctrine()->getRepository('LaPoizWindBundle:PrevisionDate')->getLastCreated($dataWindPrev);
				$tabJson->series[] = TransformeToHighchartsDataTabForJson::transformePrevisionDateList($previsionDateList);
				//$dataArray[]=$this->transformePrevisionDateListToTab($previsionDateList);
			}
			//$tabJson->data=$dataGraph;

			$spotTab = array(
				'name' => $spot->getNom(),
				'description' => $spot->getDescription(),
				'gpsLat'=> $spot->getGpsLat(),
				'gpsLong'=> $spot->getGpsLong()
				 );

			return new JsonResponse(//array(					
					//'spot' => $spotTab,
					//'dataArray' => $dataArray)
				$tabJson
			);	
		} else {
			return new JsonResponse(array(
					'success' => false,
					'description' => "Parameter not found (id of spot)"
				));
		}

	}


	/**
	 * @Template()
	*/
	public function getTestAction($id=null)
	{
		
		$message='';
		$em = $this->container->get('doctrine.orm.entity_manager');
		if (isset($id) && $id!=-1)
		{
			$spot = $em->find('LaPoizWindBundle:Spot', $id);
			if (!$spot)
			{
				return new JsonResponse(array(
					'success' => false,
					'description' => "No spot find in GetAction"
				));
			}
			// Normal way, we find spot
					
			// get all website for this spot 
			//$dataWindPrevList = $this->getDoctrine()->getRepository('LaPoizWindBundle:DataWindPrev')->getFromSpot($spot);
			
			$tabJson = TransformeToHighchartsDataTabForJson::createResultJson($spot);
			//$dataArray = array();
			 $tabJson->series=array();
			foreach ($spot->getDataWindPrev() as $dataWindPrev) {
				$previsionDateList = $this->getDoctrine()->getRepository('LaPoizWindBundle:PrevisionDate')->getLastCreated($dataWindPrev);
				$tabJson->series[] = TransformeToHighchartsDataTabForJson::transformePrevisionDateList($previsionDateList);
				//$dataArray[]=$this->transformePrevisionDateListToTab($previsionDateList);
			}
			//$tabJson->data=$dataGraph;

			$spotTab = array(
				'name' => $spot->getNom(),
				'description' => $spot->getDescription(),
				'gpsLat'=> $spot->getGpsLat(),
				'gpsLong'=> $spot->getGpsLong()
				 );

			return new JsonResponse(array(					
					'spot' => $spotTab,
					'dataGraph' => $tabJson
					)
				);	
		} else {
			return new JsonResponse(array(
					'success' => false,
					'description' => "Parameter not found (id of spot)"
				));
		}

	}

/*

	private function transformePrevisionDateListToTab($previsionDateList,$spot) {
		$listPrevisionDateTab=array();
		
		foreach ($previsionDateList as $previsionDate) {
			$previsionDateTab=array();
			$previsionDateTab["datePrev"]=$previsionDate->getDatePrev()->format('Y-m-d');
			$previsionDateTab["dateCreated"]=$previsionDate->getCreated()->format('Y-m-d');

			$listPrevisionTab=array();
			foreach ($previsionDate->getListPrevision() as $prevision) {
				$previsionTab=array();
				$previsionTab["wind"]=$prevision->getWind();
				$previsionTab["time"]=$prevision->getTime()->format('Y-m-d H:i:s');
				$previsionTab["orientation"]=$prevision->getOrientation();
				$listPrevisionTab[]=$previsionTab;
			}
			$previsionDateTab["listPrevision"]=$listPrevisionTab;
			
			$previsionDateTab["website"]= array('name'=>$previsionDate->getDataWindPrev()->getWebsite()->getNom());
			
			$listPrevisionDateTab[]=$previsionDateTab;
		}
		
		return $listPrevisionDateTab;	
	}


	private function transformeDataWindPrevToTab($dataWindPrev) {
		return $this->transformePrevisionDateListToTab($dataWindPrev->getListPrevisionDate());
	}
	*/
}