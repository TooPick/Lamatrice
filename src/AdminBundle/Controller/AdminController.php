<?php

namespace AdminBundle\Controller;

use AppBundle\Entity\Cart;
use AppBundle\Entity\Product;
use AppBundle\Enum\StatusTypeEnum;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class AdminController extends Controller
{
    public function indexAction()
    {
        return $this->render('AdminBundle:Admin:index.html.twig');
    }

    //Affiche liste des paniers validés
    public function panierValidateAction(){
        $em = $this->getDoctrine()->getManager();
        $panier_repo = $em->getRepository("AppBundle:Cart");
        $paniers = $panier_repo->findBy(
            array('validated' => true) // virgule pour AND
        );
        return $this->render('AdminBundle:Admin:Panier/panierValidate.html.twig',array(
            'paniers' => $paniers
        ));
    }

    //Affiche liste des paniers en traitement
    public function panierBeeingTreatedAction(){
        $em = $this->getDoctrine()->getManager();
        $panier_repo = $em->getRepository("AppBundle:Cart");
        $paniers = $panier_repo->findBy(
            array('status' => StatusTypeEnum::TYPE_BEING_PROCESSED) // virgule pour AND
        );
        return $this->render('AdminBundle:Admin:Panier/beeingTreated.html.twig',array(
            'paniers' => $paniers
        ));
    }


    //Affiche liste traités
    public function panierTreatedAction(){
        $em = $this->getDoctrine()->getManager();
        $panier_repo = $em->getRepository("AppBundle:Cart");
        $paniers = $panier_repo->findBy(
            array('status' => StatusTypeEnum::TYPE_PROCESSED) // virgule pour AND
        );
        return $this->render('AdminBundle:Admin:Panier/treated.html.twig',array(
            'paniers' => $paniers
        ));
    }

    public function beeingtraitedPanierAction(Cart $panier){
        $panier->setStatus(StatusTypeEnum::TYPE_BEING_PROCESSED);
        $em = $this->getDoctrine()->getManager();

        $em->persist($panier);
        $em->flush();
        return $this->redirectToRoute('admin_panierValidate');
    }

    //passe un panier en validé
    public function traitePanierAction(Cart $panier){

        $objets = $panier->getCartProducts();
        $valide = true;
        foreach ($objets as $objet){
            if($objet->getQuantity() > $objet->getProduct()->getQuantity()){
                $valide = false;
            }
        }

        if($valide){
            $panier->setStatus(StatusTypeEnum::TYPE_PROCESSED);
            $em = $this->getDoctrine()->getManager();

            $em->persist($panier);
            foreach ($objets as $objet){
              $objet->getProduct()->setQuantity($objet->getProduct()->getQuantity() - $objet->getQuantity());
              $em->persist($objet);
            }
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', "Le panier à bien été validé !");

            return $this->redirectToRoute('admin_panierValidate');
        }else{
            $this->get('session')->getFlashBag()->add('warning', "Attention, ce produit est en rupture de stocks, il n'y a pas assez de produit en stock pour le valider");
            return $this->redirectToRoute('admin_panierValidate');
        }
    }
}
