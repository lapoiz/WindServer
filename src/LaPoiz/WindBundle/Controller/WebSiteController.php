<?php
namespace LaPoiz\WindBundle\Controller;

use LaPoiz\WindBundle\Form\WebSiteForm;

use LaPoiz\WindBundle\Entity\WebSite;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\HttpFoundation\RedirectResponse;

use JMS\SecurityExtraBundle\Annotation\Secure;

class WebSiteController extends ContainerAware
{
	
	/**
	* @Template()
	*/
	public function listAction()
	{
		$message='';
		$em = $this->container->get('doctrine.orm.entity_manager');
		$websites = $em->getRepository('LaPoizWindBundle:WebSite')->findAll();
		return $this->container->get('templating')
			->renderResponse('LaPoizWindBundle:Admin:websites.html.twig',
			array('websites' => $websites, 'message' => $message));
	}
	
	/**
	* @Template()
	*/
/**	public function displayAction($id=null)
	{
		$message='';
		$em = $this->container->get('doctrine.orm.entity_manager');
		if (isset($id) && $id!=-1)
			{
				// id not null -> find website
				$webSite = $em->find('LaPoizWindBundle:WebSite', $id);
				if (!$webSite) {
					$message='Aucun webSite trouvé';
					//$message= $this->get('translator')->trans('warning.list.website.notFound');
					return $this->container->get('templating')->renderResponse(
							'LaPoizWindBundle:Default:errorPage.html.twig',
							array('errMessage' => $message ));
				} else {
					// website find
					$websites = $em->getRepository('LaPoizWindBundle:WebSite')->findAll();
					return $this->container->get('templating')
						->renderResponse('LaPoizWindBundle:Admin:website.html.twig',
						array(
							'websites' => $websites, 
							'website' => $webSite,
							'message' => $message)
						);
				}
			}
			else
			{
				
			}
			
		
	}
	*/
	/**
	* @Template()
	*/
/**	public function indexAction()
	{	
		return $this->container->get('templating')->renderResponse(
	  				  	'LaPoizWindBundle:WebSite:index.html.twig'
				);
	}
	*/
	
}