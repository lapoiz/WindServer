<?php

namespace LaPoiz\WindBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\SecurityContext;

class DefaultController extends Controller
{
    public function indexAction()
    {
        $message = '';
        $em = $this->container->get('doctrine.orm.entity_manager');
        
        // récupere tous les spots        
        $spots = $em->getRepository('LaPoizWindBundle:Spot')->findAll();
        
		return $this->container->get('templating')->renderResponse('LaPoizWindBundle:Default:index.html.twig',
			array(
				'message' => $message,
				'spots' => $spots));
    }


    public function choiceLanguageAction($langue = null)
    {
    	if($langue != null)
    	{
    		// On enregistre la langue en session
    		$this->container->get('request')->setLocale($langue);
    	}
    
    	// on tente de rediriger vers la page d'origine
    	$url = $this->container->get('request')->headers->get('referer');
    	if(empty($url)) {
    		$url = $this->container->get('router')->generate('_admin');
    	}
    	return $this->redirect($url);
    }
    
}
