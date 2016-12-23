<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AppBundle\Enum\CategoryTypeEnum;
use AppBundle\Entity\Product;
use Symfony\Component\HttpFoundation\Request;

class AppController extends Controller
{
	/**
	 * Page d'accueil du site : affiche un aperçu des produits disponibles dans les deux catégories (plastique et papeterie).
	 */
    public function indexAction()
    {
    	$em = $this->getDoctrine()->getManager();
    	$productRepository = $em->getRepository("AppBundle:Product");
    	
    	$productsStationery = $productRepository->findBy(array(
			"visible" => true,
    		"category" => CategoryTypeEnum::TYPE_STATIONERY,
    	), array('id' => 'DESC'), 8);
    	
    	$productsPlastic = $productRepository->findBy(array(
			"visible" => true,
    		"category" => CategoryTypeEnum::TYPE_PLASTIC,
    	), array('id' => 'DESC'), 8);

        return $this->render('AppBundle:App:index.html.twig', array(
        	'productsStationery' => $productsStationery,
        	'productsPlastic' => $productsPlastic,
        ));
    }

	/**
	 * Recherche un mot clef dans la base de données et affiche les produits trouvés.
	 */
	public function searchAction(Request $request) {
		$searchTerm = $request->get('search-term');

		if(count($searchTerm) <= 0) {
			return $this->redirectToRoute('app_homepage');
		}

		$em = $this->getDoctrine()->getManager();
		$productRepository = $em->getRepository('AppBundle:Product');

		$products = $productRepository->search($searchTerm);

		return $this->render('AppBundle:App:searchProductsList.html.twig', array(
			'searchTerm' => $searchTerm,
			'products' => $products,
		));
	}

	/**
	 * Affiche les produits disponibles dans la catégorie donnée en paramètre.
	 */
    public function productsListAction($type) {
    	$em = $this->getDoctrine()->getManager();
    	$productRepository = $em->getRepository("AppBundle:Product");

    	$products = array();
    	if($type == "stationery"){
    		$products = $productRepository->findBy(array(
				"visible" => true,
    			"category" => CategoryTypeEnum::TYPE_STATIONERY,
    		), array('id' => 'DESC'));
    	}else {
    		$products = $productRepository->findBy(array(
				"visible" => true,
    			"category" => CategoryTypeEnum::TYPE_PLASTIC,
    		), array('id' => 'DESC'));
    	}
    	return $this->render('AppBundle:App:productsList.html.twig', array(
    		'type' => $type,
        	'products' => $products,
        ));
    }

	/**
	 * Affiche la fiche du produit donné en paramètre.
	 */
    public function productAction(Product $product) {
		if(!$product->getVisible()) {
			throw $this->createNotFoundException('Erreur: Produit introuvable.');
		}

        return $this->render('AppBundle:App:product.html.twig', array(
            'product' => $product,
        ));
    }

	/**
	 * Page de contact (uniquement visuelle, pas d'envoi de mail pour le moment)
	 */
    public function contactAction(){
        return $this->render('AppBundle:App:contact.html.twig');
    }
}
