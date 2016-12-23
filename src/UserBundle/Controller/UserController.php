<?php

namespace UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class UserController extends Controller
{
    /**
     * Affiche les informations de l'utilisateur, ses anciens paniers, les paniers sauvegardé.
     */
    public function accountAction()
    {
        $user = $this->getUser();

        $em = $this->getDoctrine()->getManager();

        $cartRepository = $em->getRepository('AppBundle:Cart');

        $validatedCarts = $cartRepository->findBy(array(
            'user' => $user,
            'validated' => true,
        ));

        $unvalidatedCarts = $cartRepository->findBy(array(
            'user' => $user,
            'validated' => false,
        ));
        return $this->render('UserBundle:Profile:show.html.twig', array(
            'user' => $user,
            'validatedCarts' => $validatedCarts,
            'unvalidatedCarts' => $unvalidatedCarts,
        ));
    }
}
