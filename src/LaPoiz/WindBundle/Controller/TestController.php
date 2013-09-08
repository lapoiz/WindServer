<?php

namespace LaPoiz\WindBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\SecurityContext;

use LaPoiz\WindBundle\Entity\Spot;
use LaPoiz\WindBundle\Entity\DataWindPrev;
use LaPoiz\WindBundle\Form\DataWindPrevType;
use LaPoiz\WindBundle\Form\SpotType;


class TestController extends Controller
{
    public function indexAction()
    {
        return $this->container->get('templating')->renderResponse('LaPoizWindBundle:Test:tests.html.twig');
    }

    public function notificationsAction()
    {
		return $this->container->get('templating')->renderResponse('LaPoizWindBundle:Test:testNotification.html.twig');
    }

    public function multiselectAction()
    {
		return $this->container->get('templating')->renderResponse('LaPoizWindBundle:Test:testMultiselect.html.twig');
    }

    public function phpInfoAction()
    {
		return $this->container->get('templating')->renderResponse('LaPoizWindBundle:Test:testPHPInfo.html.php');
    }

    public function createSpotAction()
    {
        $spot=new Spot();
        $form=$this->createForm(new SpotType(), $spot);
        return $this->container->get('templating')->renderResponse('LaPoizWindBundle:Test:testCreateSpot.html.twig', array(
                'spot' => $spot,
                'form' => $form->createView()
        ));
    }

    public function createDataWindPrevAction(Spot $spot)
    {
        $dataWindPrev=new DataWindPrev();
        $dataWindPrev->setSpot($spot);
        $form=$this->createForm(new DataWindPrevType(), $dataWindPrev);
                
        return $this->render('LaPoizWindBundle:Test:testCreateDataWindPrev.html.twig', array(
                'dataWindPrev' => $dataWindPrev,
                'spot' => $spot,
                'form' => $form->createView()
        ));
    }

}
