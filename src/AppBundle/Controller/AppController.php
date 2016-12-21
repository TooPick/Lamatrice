<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AppBundle\Enum\CategoryTypeEnum;
use AppBundle\Entity\Product;

class AppController extends Controller
{
    public function indexAction()
    {
    	$em = $this->getDoctrine()->getManager();
    	$productRepository = $em->getRepository("AppBundle:Product");
    	
    	$productsStationery = $productRepository->findBy(array(
			"visible" => true,
    		"category" => CategoryTypeEnum::TYPE_STATIONERY,
    	), null, 8);
    	
    	$productsPlastic = $productRepository->findBy(array(
			"visible" => true,
    		"category" => CategoryTypeEnum::TYPE_PLASTIC,
    	), null, 8);

        return $this->render('AppBundle:App:index.html.twig', array(
        	'productsStationery' => $productsStationery,
        	'productsPlastic' => $productsPlastic,
        ));
    }

    public function productsListAction($type) {
    	$em = $this->getDoctrine()->getManager();
    	$productRepository = $em->getRepository("AppBundle:Product");

    	$products = array();
    	if($type == "stationery"){
    		$products = $productRepository->findBy(array(
				"visible" => true,
    			"category" => CategoryTypeEnum::TYPE_STATIONERY,
    		));
    	}else {
    		$products = $productRepository->findBy(array(
				"visible" => true,
    			"category" => CategoryTypeEnum::TYPE_PLASTIC,
    		));
    	}
    	return $this->render('AppBundle:App:productsList.html.twig', array(
    		'type' => $type,
        	'products' => $products,
        ));
    }

    public function productAction(Product $product) {
		if(!$product->getVisible()) {
			throw $this->createNotFoundException('Erreur: Produit introuvable.');
		}

        return $this->render('AppBundle:App:product.html.twig', array(
            'product' => $product,
        ));
    }

    public function contactAction(){
        return $this->render('AppBundle:App:contact.html.twig');
    }

    public function testAction()
    {
    	return $this->render('AppBundle:App:base.html.twig');
    }
}
