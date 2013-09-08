<?php
namespace LaPoiz\WindBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use LaPoiz\WindBundle\Form\DataWindPrevType;

use LaPoiz\WindBundle\Entity\Spot;
use LaPoiz\WindBundle\Entity\DataWindPrev;
use LaPoiz\WindBundle\Entity\PrevisionDate;

use LaPoiz\WindBundle\core\websiteDataManage\WindguruGetData;
use LaPoiz\WindBundle\core\websiteDataManage\MeteoFranceGetData;
use LaPoiz\WindBundle\core\websiteDataManage\MeteorologicGetData;
use LaPoiz\WindBundle\core\WindData;

use SaadTazi\GChartBundle\DataTable;
use SaadTazi\GChartBundle\Chart\PieChart;

class AjaxDataWindPrevController extends Controller
{
	
	/**
	 * @Template()
	 */
	// for testing: http://localhost/WindServer/web/app_dev.php/ajax/dataWindPrev/edit/2
	public function ajaxEditAction($id=null)
	{
		if (isset($id)) {
			$em = $this->container->get('doctrine.orm.entity_manager');
			$dataWindPrev = $em->find('LaPoizWindBundle:DataWindPrev', $id);

			if (!$dataWindPrev)
			{
				return $this->container->get('templating')->renderResponse(
						'LaPoizWindBundle:Default:errorBlock.html.twig',
						array('errMessage' => "No dataWindPrev find !"));
			}
			$form=$this->createForm(new DataWindPrevType(), $dataWindPrev);
				
			return $this->render('LaPoizWindBundle:DataWindPrev:blockEdit.html.twig', array(
				'dataWindPrev' => $dataWindPrev,
				'form' => $form->createView()
			));
		} else {	
			return $this->container->get('templating')->renderResponse(
				'LaPoizWindBundle:Default:errorPage.html.twig',
				array(	'errMessage' => "Error bad parameter (dataWindPrev.id miss)" ));
		}
	}

	/**
	 * @Template()
	 */
	// for testing: http://localhost/WindServer/web/app_dev.php/ajax/dataWindPrev/history/data/2
	public function historyDataAction($id=null)
	{
		$message='';
		if (isset($id)) {
			$date=date('Y-m-d');
			$result=$this->historyDataForDatePrev($id,$date);
			
			return $this->container->get('templating')->renderResponse(
				'LaPoizWindBundle:Ajax:dataWindPrevHistoryData.html.twig',
				$result);
			
		} else {	
			return $this->container->get('templating')->renderResponse(
				'LaPoizWindBundle:Default:errorPage.html.twig',
				array(	'errMessage' => "Error bad parameter (dataWindPrev.id miss)" ));
		}
	}
	
	/**
	* @Template()
	*/
	// for testing: http://localhost/WindServer/web/app_dev.php/ajax/dataWindPrev/history/data/date_prev/2/2012-02-25
	public function historyDataForDatePrevAction($id=null,$date=null)
	{
		$message='';
		if (isset($id)) {
			$result=$this->historyDataForDatePrev($id,$date);
			
			return $this->container->get('templating')->renderResponse(
				'LaPoizWindBundle:Ajax:blockHistoryDataDisplayInfo.html.twig',
				$result);
		} else {
			return $this->container->get('templating')->renderResponse(
				'LaPoizWindBundle:Default:errorPage.html.twig',
				array(	'errMessage' => "Error bad parameter (dataWindPrev.id miss)" ));
		}
	}
		
	public function historyDataForDatePrev($id,$date) {
		$em = $this->container->get('doctrine.orm.entity_manager');
		$dataWindPrev = $em->find('LaPoizWindBundle:DataWindPrev', $id);
		$listPrevisionDate = $this->getDoctrine()
			->getRepository('LaPoizWindBundle:PrevisionDate')
			->getFromOneWebSiteForOneDay($id,$date);
		return array('date' => $date ,
					'dataWindPrev' => $dataWindPrev,
					'listPrevisionDate' =>$listPrevisionDate);
	}
	
	/**
	* @Template()
	*/
	// for testing: http://localhost/WindServer/web/app_dev.php/ajax/dataWindPrev/history/data/date_analyse/2/2012-02-21
	public function historyDataFromDateAnalyseAction($id=null,$date=null)
	{
		$message='';
		if (isset($id)) {
			$result=$this->historyDataFromDateAnalyse($id,$date);
			return $this->container->get('templating')->renderResponse(
						'LaPoizWindBundle:Ajax:blockHistoryDataFromDateAnalyseDisplayInfo.html.twig',
						$result);
		} else {
			return $this->container->get('templating')->renderResponse(
			'LaPoizWindBundle:Default:errorPage.html.twig',
				array(	'errMessage' => "Error bad parameter (dataWindPrev.id miss)" ));
		}
	}
	
	public function historyDataFromDateAnalyse($id,$date) {
		$em = $this->container->get('doctrine.orm.entity_manager');
		$dataWindPrev = $em->find('LaPoizWindBundle:DataWindPrev', $id);
		$listPrevisionDate = $this->getDoctrine()
			->getRepository('LaPoizWindBundle:PrevisionDate')
			->getFromOneWebSiteFromAnalyseDateOneDay($id,$date);		
			return array(	
				'date' => $date,
				'dataWindPrev' => $dataWindPrev ,
				'listPrevisionDate' =>$listPrevisionDate);
	}
		
	/**
	* @Template()
	*/
	// for testing: http://localhost/WindServer/web/app_dev.php/ajax/dataWindPrev/history/analyse/2
	public function historyAnalyseAction($id=null)
	{
		$message='';
		if (isset($id)) {
			$date=date('Y-m-d');
			$em = $this->container->get('doctrine.orm.entity_manager');
			$listPrevisionDate = $this->getDoctrine()
				->getRepository('LaPoizWindBundle:PrevisionDate')
				->getFromOneWebSiteForOneMonth($id,$date);
			
			$tabHistoryAnalyse=$this->buildHistoryAnalyse($listPrevisionDate);
			return $this->container->get('templating')->renderResponse(
					'LaPoizWindBundle:Ajax:blockHistoryAnalyse.html.twig',			
					array('tabHistoryAnalyse' =>$tabHistoryAnalyse));
		} else {
			return $this->container->get('templating')->renderResponse(
				'LaPoizWindBundle:Default:errorPage.html.twig',
				array(	'errMessage' => "Error bad parameter (dataWindPrev.id miss)" ));
		}
	}
		
	/*
	 * return array(date created, array(datePrev,nbElem))
	 */
	private function buildHistoryAnalyse($listPrevisionDate) {
		$result=array();
		$firstLine=true;
		$dateCreated='';
		$line=array();
		foreach ($listPrevisionDate as $previsionDate) {
			if ($previsionDate->getCreated()<>$dateCreated) {
				// change line
				if ($firstLine) {
					$firstLine=false;				
				} else {
					// add previous line
					$result[]=array($dateCreated,$line);
				}
				$line=array();
				$dateCreated=$previsionDate->getCreated();				
			}
			$line[]=array($previsionDate->getDatePrev(),count($previsionDate->getListPrevision()));			
		}
		$result[]=array($dateCreated,$line);
		return $result;
	}
	
	
}