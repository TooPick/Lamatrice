<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AppBundle\Enum\CategoryTypeEnum;

class AppController extends Controller
{
    public function indexAction()
    {
    	$em = $this->getDoctrine()->getManager();
    	$productRepository = $em->getRepository("AppBundle:Product");
    	
    	$productsStationery = $productRepository->findBy(array(
    		"category" => CategoryTypeEnum::TYPE_STATIONERY,
    	), null, 10);
    	
    	$productsPlastic = $productRepository->findBy(array(
    		"category" => CategoryTypeEnum::TYPE_PLASTIC,
    	), null, 10);

        return $this->render('AppBundle:App:index.html.twig', array(
        	'productsStationery' => $productsStationery,
        	'productsPlastic' => $productsPlastic,
        ));
    }

    public function testAction()
    {
    	return $this->render('AppBundle:App:base.html.twig');
    }
}
