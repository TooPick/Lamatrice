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
        $user = $this->getUser();

        $em = $this->getDoctrine()->getManager();

        $cart = null;
        if($currentCartId != null) {
            $cartRepository = $em->getRepository('AppBundle:Cart');

            $cart = $cartRepository->find($currentCartId);
        }

        if($cart == null)
            $cart = new Cart();

        return $this->render('AppBundle:Cart:showCart.html.twig', array(
            'cart' => $cart,
        ));
    }

    public function addProductAction(Request $request, Product $product, $quantity)
    {
        $currentCartId = $this->get('session')->get('user_current_cart_id');
        $user = $this->getUser();

        $em = $this->getDoctrine()->getManager();

        $cart = null;
        if($currentCartId != null) {
            $cartRepository = $em->getRepository('AppBundle:Cart');

            $cart = $cartRepository->find($currentCartId);

            if($cart->getValidated()) {
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
}
