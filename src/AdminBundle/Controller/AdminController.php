<?php

namespace AdminBundle\Controller;

use AppBundle\Entity\Cart;
use AppBundle\Entity\Product;
use AppBundle\Enum\StatusTypeEnum;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class AdminController extends Controller
{
    /**
     * Affiche l'index du menu administrateur.
     **/
    public function indexAction()
    {
        return $this->render('AdminBundle:Admin:index.html.twig');
    }

    /**
     * Affiche la liste de tous les paniers validés par les utilisateurs, c'est à dire les paniers que l'utilisateur à "payés"
    **/
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

    /**
     * Affiche la liste de tous les paniers que l'administrateur a décidé de mettre en cours de traitement
     **/
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

    /**
     * Affiche la liste de tous les paniers traités par l'administrateur.
     **/
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

    /**
     * Prend un panier en parametre et change le status du panier en " en cours de traitement "
     **/
    public function beeingtraitedPanierAction(Cart $panier){
        $panier->setStatus(StatusTypeEnum::TYPE_BEING_PROCESSED);
        $em = $this->getDoctrine()->getManager();

        $em->persist($panier);
        $em->flush();
        return $this->redirectToRoute('admin_panierValidate');
    }

    /**
     * Prend un panier en parametre et change le status en validé uniquement, si le nombre d'objets en stock est suffisant.
     **/
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
