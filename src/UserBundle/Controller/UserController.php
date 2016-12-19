<?php

namespace UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class UserController extends Controller
{
    public function indexAction()
    {
        return $this->render('UserBundle:User:index.html.twig');
    }

    public function accountAction()
    {
        //TODO vérif connecté
        $user = $this->getUser();
        return $this->render('UserBundle:Profile:show.html.twig', array(
            'user' => $user,
        ));
    }
}
