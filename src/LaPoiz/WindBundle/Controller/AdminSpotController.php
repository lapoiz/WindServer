<?php
namespace LaPoiz\WindBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use LaPoiz\WindBundle\Form\SpotType;
use LaPoiz\WindBundle\Form\DataWindPrevType;
use LaPoiz\WindBundle\Form\BaliseForm;

use LaPoiz\WindBundle\Entity\Spot;
use LaPoiz\WindBundle\Entity\Balise;
use LaPoiz\WindBundle\Entity\DataWindPrev;

class AdminSpotController extends Controller

{	
	/**
	* @Template()
	*/
	public function listAction()
	{
		$em = $this->container->get('doctrine.orm.entity_manager');
		$spots = $em->getRepository('LaPoizWindBundle:Spot')->findAll();
		return $this->container->get('templating')->renderResponse('LaPoizWindBundle:Admin:spot.html.twig',
				array(	'spots' => $spots,
						'spot' => $spots[0]));
	}
	
	/**
	* @Template()
	*/
	public function ajaxGetListSpotsAction()
	{
		$em = $this->container->get('doctrine.orm.entity_manager');
		$spots = $em->getRepository('LaPoizWindBundle:Spot')->findAll();
		return $this->container->get('templating')->renderResponse('LaPoizWindBundle:Admin:blockAjaxListSpots.html.twig',
				array(	'spots' => $spots));
	}

	/**
	* @Template()	
	*/
	// For testing: http://localhost/WindServer/web/app_dev.php/ajax/spot/get/addForm
	public function ajaxGetAddFormAction()
	{	
		$spot=new Spot();
		return $this->edit($spot);
	}


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
			// Normal way
			$spots = $em->getRepository('LaPoizWindBundle:Spot')->findAll();
			return $this->container->get('templating')->renderResponse(
				'LaPoizWindBundle:Admin:spot.html.twig',
				//'LaPoizWindBundle:Test:testJQueryDialog.html.twig',
				array(
					'message' => $message,
					'spot'=>$spot,
					'spots' => $spots));
		} else {
			return $this->container->get('templating')->renderResponse(
				'LaPoizWindBundle:Default:errorPage.html.twig',
				array('errMessage' => "Parameter not found (id of spot)"));
		}

	}

	

	/**
	 * @Template()
	 */
	// For testing: http://localhost/WindServer/web/app_dev.php/ajax/spot/desc/1
	public function ajaxDescriptionAction($id=null)
	{
		$message='';
		$em = $this->container->get('doctrine.orm.entity_manager');
		if (isset($id) && $id!=-1)
		{
			$spot = $em->find('LaPoizWindBundle:Spot', $id);
			if (!$spot)
			{
				return $this->container->get('templating')->renderResponse(
						'LaPoizWindBundle:Default:errorBlock.html.twig',
						array('errMessage' => "No spot find !"));
			}
			return $this->render('LaPoizWindBundle:Spot:displayDescription.html.twig', array(
					'spot' => $spot,
					'message' => $message
			));
		}
	}
	

	/**
	 * @Template()
	 */
	// For testing: http://localhost/WindServer/web/app_dev.php/ajax/spot/edit/1
	public function ajaxEditAction($id=null)
	{
		$em = $this->container->get('doctrine.orm.entity_manager');
		if (isset($id) && $id!=-1)
		{
			$spot = $em->find('LaPoizWindBundle:Spot', $id);
			if (!$spot)
			{
				return $this->container->get('templating')->renderResponse(
						'LaPoizWindBundle:Default:errorBlock.html.twig',
						array('errMessage' => "No spot find !"));
			}
			// spot find
			return $this->edit($spot);
		}
	}

	private function edit($spot) {
		$form=$this->createForm(new SpotType(), $spot);
				
		return $this->render('LaPoizWindBundle:Spot:blockEdit.html.twig', array(
				'spot' => $spot,
				'form' => $form->createView()
		));
	}


	/**
	 * @Template()
	 */
	// For testing: http://localhost/WindServer/web/app_dev.php/ajax/spot/add
	public function ajaxCreateAction()
	{
		//$this->get('logger')->err('ajaxCreateAction');
		$spot=new Spot();
		return $this->updateSpot($spot); // pb here with the "intl" extension
		//
	}

	/**
	 * @Template()
	 */
	public function ajaxUpdateAction($id=null)
	{
		$message='';
		$em = $this->container->get('doctrine.orm.entity_manager');
		if (isset($id))
		{
			if ($id==-1) {
				// create one
				$spot=new Spot();
			} else {
				$spot = $em->find('LaPoizWindBundle:Spot', $id);
			}
			if (!$spot)
			{
				return $this->container->get('templating')->renderResponse(
						'LaPoizWindBundle:Default:errorBlock.html.twig',
						array('errMessage' => "No spot find !"));
			}
			// spot find
			return $this->updateSpot($spot);
		}
	}
	
	private function updateSpot($spot) {
 		
		try {
			$em = $this->container->get('doctrine.orm.entity_manager');
			$form=$this->createForm(new SpotType(), $spot);
					
			$request = $this->getRequest();
			$form->bind($request);
			//FormInterface::bind();
			if ($form->isValid()) {
				$em->persist($spot);
				$em->flush();
				//return $this->displayAction($id,true);
				return new JsonResponse(array('success' => true));
			}
			// form not valid
			$blockEdit=$this->render('LaPoizWindBundle:Spot:blockEdit.html.twig', 
				array(
					'spot' => $spot,
					'form' => $form->createView())
				);
			return new JsonResponse(array(
				'success' => false,
				'form' => $blockEdit->getContent()
				)
			);
		} catch(\Exception $e){
			//$info = toString($e);
 		 	$this->get('logger')->err('Une erreur est survenue');
			$this->get('logger')->err($e);
    		return $this->container->get('templating')->renderResponse(
						'LaPoizWindBundle:Default:errorBlock.html.twig',
						array('errMessage' => "No spot find !"));
		}
	}

	/**
	 * @Template()
	*/ 
	public function deleteAction($id)
	{
		$em = $this->container->get('doctrine.orm.entity_manager');
		$spot = $em->find('LaPoizWindBundle:Spot', $id);
		if (!$spot)
		{
			return $this->container->get('templating')->renderResponse(
						'LaPoizWindBundle:Default:errorBlock.html.twig',
						array('errMessage' => "No spot find !"));
		}
		// spot find
		$spotId = $spot->getId();
		$em->remove($spot);
		$em->flush();

		// display page
		
		$notification = array ('type'=>'success',
				 'title'=>$this->get('translator')->trans('notification.info.spot.delete.title'),
				 'content'=>$this->get('translator')->trans('notification.info.spot.delete.content'));
		
		$spots = $em->getRepository('LaPoizWindBundle:Spot')->findAll();
		return $this->container->get('templating')->renderResponse('LaPoizWindBundle:Admin:spot.html.twig',
				array(	'spots' => $spots,
						'spot' => $spots[0],
						'notification' => $notification));
	}


	/**
	 * @Template()
	*/ 
	public function ajaxGetListWebsitesAction($id) {
		$em = $this->container->get('doctrine.orm.entity_manager');
		$spot = $em->find('LaPoizWindBundle:Spot', $id);
		
		return $this->container->get('templating')->renderResponse('LaPoizWindBundle:Admin:blockListWebsiteOfSpot.html.twig',
				array(	'spot' => $spot));
	}


	/**
	 * @Template()
	*/ 
	public function ajaxAddWebsiteAction(){		
		$em = $this->container->get('doctrine.orm.entity_manager');
		$dataWindPrev = new DataWindPrev();
		$form=$this->createForm(new DataWindPrevType(), $dataWindPrev);
				
		$request = $this->getRequest();
		$form->bind($request);
		if ($form->isValid()) {
			$website=$dataWindPrev->getWebsite();
			$spot=$dataWindPrev->getSpot();

			$website->addDataWindPrev($dataWindPrev);
			$spot->addDataWindPrev($dataWindPrev);

			$em->persist($spot);
			$em->persist($website);
			$em->persist($dataWindPrev);

			$em->flush();
			
			return new JsonResponse(array('success' => true));
		}
		// form not valid
		$blockEdit=$this->render('LaPoizWindBundle:DataWindPrev:blockEdit.html.twig', 
			array(
				'dataWindPrev' => $dataWindPrev,
				'form' => $form->createView())
			);
		return new JsonResponse(array(
			'success' => false,
			'form' => $blockEdit->getContent()
			)
		);
	}


	/**
	 * @Template()
	*/ 
	public function ajaxGetAddWebsiteFormAction($id){

		$em = $this->container->get('doctrine.orm.entity_manager');
		$spot = $em->find('LaPoizWindBundle:Spot', $id);
		if (!$spot)
		{
			return $this->container->get('templating')->renderResponse(
						'LaPoizWindBundle:Default:errorBlock.html.twig',
						array('errMessage' => "No spot find !"));
		}
		// spot find
		$dataWindPrev=new DataWindPrev();
		$dataWindPrev->setSpot($spot);
		$form=$this->createForm(new DataWindPrevType(), $dataWindPrev);
				
		return $this->render('LaPoizWindBundle:DataWindPrev:blockEdit.html.twig', array(
				'dataWindPrev' => $dataWindPrev,
				'spot' => $spot,
				'form' => $form->createView()
		));
	}
}