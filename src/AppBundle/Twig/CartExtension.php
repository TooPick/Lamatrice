<?php

namespace AppBundle\Twig;

use AppBundle\Enum\StatusTypeEnum;
use Symfony\Component\DependencyInjection\Container;
use AppBundle\Entity\Cart;

class CartExtension extends \Twig_Extension {

    protected $doctrine;
    protected $container;

    public function __construct($doctrine, Container $container)
    {
        $this->doctrine = $doctrine;
        $this->container = $container;
    }

    public function getName()
    {
        return 'cart_extension';
    }

    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('getCurrentCart', [$this, 'getCurrentCart'], ['is_safe' => ['html']]),
            new \Twig_SimpleFunction('getCartProductsNumber', [$this, 'getCartProductsNumber'], ['is_safe' => ['html']]),
            new \Twig_SimpleFunction('getStatusLabel', [$this, 'getStatusLabel'], ['is_safe' => ['html']]),
        ];
    }

    private function retrieveCart()
    {
        $currentCartId = $this->container->get('session')->get('user_current_cart_id');

        $em = $this->doctrine->getManager();

        $cart = null;
        if($currentCartId != null) {
            $cartRepository = $em->getRepository('AppBundle:Cart');

            $cart = $cartRepository->find($currentCartId);
        }

        if($cart == null)
            $cart = new Cart();

        return $cart;
    }

    public function getCurrentCart()
    {
        $cart = $this->retrieveCart();
        return $cart;
    }

    public function getCartProductsNumber()
    {
        $cart = $this->retrieveCart();
        $products = $cart->getCartProducts();

        $count = 0;
        foreach ($products as $product)
            $count += $product->getQuantity();

        return $count;
    }

    public function getStatusLabel($name)
    {
        return StatusTypeEnum::getTypeName($name);
    }
}