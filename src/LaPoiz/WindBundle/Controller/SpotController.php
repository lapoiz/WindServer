<?php
namespace LaPoiz\WindBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use LaPoiz\WindBundle\Form\SpotForm;
use LaPoiz\WindBundle\Form\BaliseForm;

use LaPoiz\WindBundle\Entity\Spot;
use LaPoiz\WindBundle\Entity\Balise;
use LaPoiz\WindBundle\Entity\DataWindPrev;

use LaPoiz\WindBundle\core\graph\TransformeToHighchartsData;
use LaPoiz\WindBundle\core\graph\TransformeToHighchartsDataTabForJson;


class SpotController extends Controller

{	
	
	/**
	* @Template()
	*/
/**	public function indexAction()
	{
		$em = $this->container->get('doctrine.orm.entity_manager');
		$spots = $em->getRepository('LaPoizWindBundle:Spot')->findAll();
		return $this->container->get('templating')->renderResponse('LaPoizWindBundle:Spot:index.html.twig',
			array('spots' => $spots));
	}
*/	
	/**
	* @Template()
	*/
	public function displayAction($id=null)
	{
		$message='';
		$em = $this->container->get('doctrine.orm.entity_manager');
		if (isset($id) && $id!=-1)
		{
			$spot = $em->find('LaPoizWindBundle:Spot', $id);
			if (!$spot)
			{
				return $this->container->get('templating')->renderResponse(
					'LaPoizWindBundle:Default:errorPage.html.twig',
					array('errMessage' => "No spot find in DisplayAction" ));
			}
			// Normal way, we find spot
			
			// get all spots (for nav)
			$spots = $em->getRepository('LaPoizWindBundle:Spot')->findAll();
			
			// get all dataWindPrev from spot
			$dataWindPrevList = $this->getDoctrine()->getRepository('LaPoizWindBundle:DataWindPrev')->getFromSpot($spot);
			
			$dataGraphArray = array();
			 
			foreach ($dataWindPrevList as $dataWindPrev) {
				$previsionDateList = $this->getDoctrine()->getRepository('LaPoizWindBundle:PrevisionDate')->getLastCreated($dataWindPrev);
				$dataGraph = TransformeToHighchartsData::transformePrevisionDateList($previsionDateList);
				$jsonData = TransformeToHighchartsDataTabForJson::transformePrevisionDateList($previsionDateList);
				$dataGraphArray[]= array("dataWindPrev"=>$dataWindPrev, "dataGraph"=>$dataGraph, "jsonData"=>$jsonData);
			}
			
			$xAxisGraphData = TransformeToHighchartsData::createElemForXAxisHighchart($dataGraphArray);
			
			return $this->container->get('templating')->renderResponse(
				'LaPoizWindBundle:Spot:display.html.twig',
				array(
					'message' => $message,
					'spot'=>$spot,
					'spots' => $spots,
					'dataGraphArray' => $dataGraphArray,
					'xAxisGraphData' => $xAxisGraphData));
		} else {
			return $this->container->get('templating')->renderResponse(
				'LaPoizWindBundle:Default:errorPage.html.twig',
				array('errMessage' => "Parameter not found (id of spot)"));
		}

	}

}