<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Cart;
use AppBundle\Entity\CartProduct;
use AppBundle\Entity\Product;
use AppBundle\Enum\StatusTypeEnum;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class CartController extends Controller
{
    public function showCartAction()
    {
        $currentCartId = $this->get('session')->get('user_current_cart_id');

        $em = $this->getDoctrine()->getManager();

        $cart = null;
        if($currentCartId != null) {
            $cartRepository = $em->getRepository('AppBundle:Cart');

            $cart = $cartRepository->find($currentCartId);
        }

        if($cart == null)
            $cart = new Cart();

        return $this->render('AppBundle:Cart:cart.html.twig', array(
            'cart' => $cart,
        ));
    }

    public function previewCartAction(Cart $cart)
    {
        $user = $this->getUser();
        if($cart->getUser() != $user)
            throw $this->createNotFoundException('Erreur : Panier introuvable.');

        return $this->render('AppBundle:Cart:preview.html.twig', array(
            'cart' => $cart,
        ));
    }

    public function addProductAction(Product $product, $quantity)
    {
        $currentCartId = $this->get('session')->get('user_current_cart_id');
        $user = $this->getUser();

        $em = $this->getDoctrine()->getManager();

        $cart = null;
        if($currentCartId != null) {
            $cartRepository = $em->getRepository('AppBundle:Cart');

            $cart = $cartRepository->find($currentCartId);

            if($cart == null || $cart->getValidated()) {
                $cart = null;
            }
        }

        if($cart == null) {
            $cart = new Cart();
            $cart->setUser($user);
            $cart->setStatus(StatusTypeEnum::TYPE_UNTREATED);
            $cart->setValidated(false);

            $em->persist($cart);
            $em->flush();

            $this->get('session')->set('user_current_cart_id', $cart->getId());
        }

        if($cart != null) {
            if($cart->getValidated()) {
                $this->get('session')->getFlashBag()->add('warning', "Le panier choisi a déjà été validé.");
                return $this->redirectToRoute('app_homepage');
            }

            if(!$product->getVisible()) {
                throw $this->createNotFoundException('Erreur: Produit introuvable.');
            }

            if($product->getQuantity() <= 0)
                $this->get('session')->getFlashBag()->add('warning', "Attention, ce produit est en rupture de stocks, il n'a pas été ajouté au panier.");
            else {
                $cartProductRepository = $em->getRepository('AppBundle:CartProduct');

                $cartProduct = $cartProductRepository->findOneBy(array(
                    'cart' => $cart,
                    'product' => $product,
                ));

                if($cartProduct == null) {
                    $cartProduct = new CartProduct();
                    $cartProduct->setCart($cart);
                    $cartProduct->setProduct($product);
                    $cartProduct->setQuantity($quantity);
                } else {
                    $cartProduct->setQuantity($cartProduct->getQuantity() + $quantity);
                }

                if($product->getQuantity() < $cartProduct->getQuantity())
                    $this->get('session')->getFlashBag()->add('warning', "Attention, il n'y a pas assez de produits en stocks pour la quantité demandée.");
                else {
                    $em->persist($cartProduct);
                    $em->flush();

                    $this->get('session')->getFlashBag()->add('success', "Le produit a bien été ajouté au panier.");
                }
            }
        }

        //TODO : redirect vers page article ou panier
        return $this->redirectToRoute('app_homepage');
    }

    public function removeProductAction(Product $product)
    {
        $currentCartId = $this->get('session')->get('user_current_cart_id');

        $em = $this->getDoctrine()->getManager();

        $cart = null;
        if($currentCartId != null) {
            $cartRepository = $em->getRepository('AppBundle:Cart');

            $cart = $cartRepository->find($currentCartId);

            if($cart != null) {
                if($cart->getValidated()) {
                    $this->get('session')->getFlashBag()->add('warning', "Le panier choisi a déjà été validé.");
                    return $this->redirectToRoute('app_homepage');
                }

                $cartProductRepository = $em->getRepository('AppBundle:CartProduct');
                $cartProduct = $cartProductRepository->findOneBy(array(
                    'cart' => $cart,
                    'product' => $product,
                ));

                if($cartProduct != null) {
                    $em->remove($cartProduct);
                    $em->flush();

                    $this->get('session')->getFlashBag()->add('success', "Le produit a bien été retiré du panier.");

                    if(count($cart->getCartProducts()) <= 0) {
                        $em->remove($cart);
                        $em->flush();
                    }
                }
            }
        }

        //TODO : redirect vers page article ou panier
        return $this->redirectToRoute('app_homepage');
    }

    public function useCartAction(Cart $cart)
    {
        $user = $this->getUser();
        if($cart->getUser() == $user) {

            if($cart->getValidated())
                $this->get('session')->getFlashBag()->add('warning', "Le panier choisi a déjà été validé.");
            else {
                $this->get('session')->set('user_current_cart_id', $cart->getId());
                $this->get('session')->getFlashBag()->add('success', "Le panier en cours d'utilisation a bien été changé.");
            }

        } else
            throw $this->createNotFoundException("Erreur : Panier introuvable.");

        return $this->redirectToRoute('user_account');
    }
    
    public function saveCartAction()
    {
        $this->get('session')->set('user_current_cart_id', null);
        $this->get('session')->getFlashBag()->add('success', "Le panier a bien été sauvegardé, vous pouvez le retrouver dans la section \"Mon Compte\".");

        return $this->redirectToRoute('app_homepage');
    }

    public function deleteCartAction(Cart $cart)
    {
        $user = $this->getUser();
        if($cart->getUser() != $user)
            throw $this->createNotFoundException("Erreur : Panier introuvable.");

        if($cart->getValidated()) {
            $this->get('session')->getFlashBag()->add('warning', "Vous ne pouvez pas supprimer un panier qui a déjà été validé.");

        } else {
            $em = $this->getDoctrine()->getManager();
            $em->remove($cart);
            $em->flush();

            $this->get('session')->getFlashBag()->add('success', "Le panier a bien été supprimé.");
        }

        return $this->redirectToRoute('user_account');
    }

    public function checkoutAction($state)
    {
        $currentCartId = $this->get('session')->get('user_current_cart_id');

        $em = $this->getDoctrine()->getManager();

        $cart = null;
        if($currentCartId != null) {
            $cartRepository = $em->getRepository('AppBundle:Cart');
            $cart = $cartRepository->find($currentCartId);
        }

        if($cart == null) {
            $this->get('session')->getFlashBag()->add('warning', "Attention le panier est vide.");
            return $this->redirectToRoute('app_cart_show');
        }

        if($cart->getValidated()) {
            $this->get('session')->getFlashBag()->add('warning', "Le panier choisi a déjà été validé.");
            return $this->redirectToRoute('app_homepage');
        }

        if($state == "validate") {

            $cart->setValidated(true);
            $em->persist($cart);
            $em->flush();

            $this->get('session')->set('user_current_cart_id', null);
            $this->get('session')->getFlashBag()->add('success', "Le paiement a bien été validé, votre panier est en attente de la validation d'un magasinier.");

            return $this->redirectToRoute('app_homepage');

        } else {
            return $this->render('AppBundle:Cart:checkout.html.twig', array(
                'cart' => $cart,
            ));
        }
    }
}
