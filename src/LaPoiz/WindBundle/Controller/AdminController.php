<?php
namespace LaPoiz\WindBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class AdminController extends Controller

{
  /**
     * @Route("/", name="_admin")
     * @Template()
     */
  public function indexAction()
  {
    $message = '';
    return $this->container->get('templating')->renderResponse('LaPoizWindBundle:Admin:index.html.twig',
      array('message' => $message));
  }
}