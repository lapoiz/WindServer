<?php
namespace LaPoiz\WindBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use LaPoiz\WindBundle\Form\SpotForm;
use LaPoiz\WindBundle\Form\DataWindPrevForm;

use LaPoiz\WindBundle\Entity\Spot;
use LaPoiz\WindBundle\Entity\DataWindPrev;
use LaPoiz\WindBundle\Entity\PrevisionDate;

use LaPoiz\WindBundle\core\websiteDataManage\WindguruGetData;
use LaPoiz\WindBundle\core\websiteDataManage\MeteoFranceGetData;
use LaPoiz\WindBundle\core\websiteDataManage\MeteorologicGetData;
use LaPoiz\WindBundle\core\WindData;

use SaadTazi\GChartBundle\DataTable;
use SaadTazi\GChartBundle\Chart\PieChart;

class AdminDataWindPrevController extends Controller
{

	/**
	 * @Template()
	*/ 
	public function displayAction($id=null)
	{
		$message='';
		$em = $this->container->get('doctrine.orm.entity_manager');

		if (isset($id)) {
			$dataWindPrev = $em->find('LaPoizWindBundle:DataWindPrev', $id);
		}
		return $this->container->get('templating')->renderResponse(
  		'LaPoizWindBundle:Admin:dataWindPrev.html.twig',
		array(	'dataWindPrev' => $dataWindPrev,
				'spot' =>  $dataWindPrev->getSpot() ));
	}


	/**
	 * @Template()
	*/ 
	public function deleteAction(DataWindPrev $dataWindPrev)
	{
		
		$em = $this->container->get('doctrine.orm.entity_manager');

		$website=$dataWindPrev->getWebsite();
		$website->removeDataWindPrev($dataWindPrev);
        $spot=$dataWindPrev->getSpot();
        $spot->removeDataWindPrev($dataWindPrev);
		$em->remove($dataWindPrev);
		$em->persist($spot);
		$em->persist($website);

		$em->flush();
		$this->get('session')->getFlashBag()->add('notice', 'Element effacé (à traduire ds AdminDataWindPrevController)');
		return $this->redirect($this->generateUrl("_admin_spot_display", array("id"=>$spot->getId())));
	}
}