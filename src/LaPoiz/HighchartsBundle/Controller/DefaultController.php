<?php

namespace LaPoiz\HighchartsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use LaPoiz\HighchartsBundle\Core\TestHighchartsDataTransformer;

class DefaultController extends Controller
{
    
    public function indexAction()
    {
    	$highchartsData = TestHighchartsDataTransformer::testDataTransformer();
        return $this->render('LaPoizHighchartsBundle:Default:index.html.twig',
			array('highchartsData' => $highchartsData));
    }
}
