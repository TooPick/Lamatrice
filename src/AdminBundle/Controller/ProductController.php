<?php

namespace AdminBundle\Controller;

use AppBundle\Entity\Product;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Product controller.
 *
 */
class ProductController extends Controller
{
    /**
     * Liste tous les produits disponibles sur le site
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $products = $em->getRepository('AppBundle:Product')->findAll();

        return $this->render('AdminBundle:Admin:product/index.html.twig', array(
            'products' => $products,
        ));
    }
    
    /**
     * Liste tous les produits en stock dans le magasin.
     */
    public function stockAction()
    {
        $em = $this->getDoctrine()->getManager();
        $query = $em->createQuery(
    'SELECT p
    FROM AppBundle:Product p
    WHERE p.quantity > :quantity
    ORDER BY p.quantity ASC'
)->setParameter('quantity', '0');
        $products = $query->getResult();

        return $this->render('AdminBundle:Admin:product/stock.html.twig', array(
            'products' => $products,
        ));
    }

    /**
     * Créer un nouveau produit
     */
    public function newAction(Request $request)
    {
        $product = new Product();
        $form = $this->createForm('AdminBundle\Form\ProductType', $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($product);
            $em->flush($product);

            return $this->redirectToRoute('admin_product_show', array('id' => $product->getId()));
        }

        return $this->render('AdminBundle:Admin:product/new.html.twig', array(
            'product' => $product,
            'form' => $form->createView(),
        ));
    }

    /**
     * Affiche les informations d'un produit
     */
    public function showAction(Product $product)
    {
        $deleteForm = $this->createDeleteForm($product);

        return $this->render('AdminBundle:Admin:product/show.html.twig', array(
            'product' => $product,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Edite les information d'un produit.
     */
    public function editAction(Request $request, Product $product)
    {
        $deleteForm = $this->createDeleteForm($product);
        $editForm = $this->createForm('AdminBundle\Form\ProductType', $product,array(
     'attr' => array('stock' => false)));
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('admin_product_index');
        }

        return $this->render('AdminBundle:Admin:product/edit.html.twig', array(
            'product' => $product,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Edite la quantité d'un produit disponible dans les stocks.
     */
    public function editStockAction(Request $request, Product $product)
    {
        $deleteForm = $this->createDeleteForm($product);
        $editForm = $this->createForm('AdminBundle\Form\ProductType', $product,array(
     'attr' => array('stock' => true)));
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('admin_product_stock');
        }

        return $this->render('AdminBundle:Admin:product/edit.html.twig', array(
            'product' => $product,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Supprime un produit
     */
    public function deleteAction(Request $request, Product $product)
    {
        $form = $this->createDeleteForm($product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($product);
            $em->flush($product);
        }

        return $this->redirectToRoute('admin_product_index');
    }

    /**
     * Créer un formulaire pour supprimer un produit.
     */
    private function createDeleteForm(Product $product)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_product_delete', array('id' => $product->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }

    /**
     * Liste toutes les alertes sur les produits (alertes de stocks et alertes de péremption)
     */
    public function alertsAction(){
        $em = $this->getDoctrine()->getManager();

        $query = $em->createQuery(
            'SELECT p
        FROM AppBundle:Product p
        WHERE p.quantity <= p.quantityAlert
        ORDER BY p.quantity ASC'
        );
        $products = $query->getResult();

        $query = $em->createQuery(
            'SELECT p
        FROM AppBundle:Product p
        WHERE CURRENT_DATE() >= p.expirationDate
        ORDER BY p.quantity ASC'
        );
        $productsP = $query->getResult();

        return $this->render('AdminBundle:Admin:product/alerts.html.twig', array(
            'products' => $products,
            'productsP' => $productsP,
        ));
    }
}
