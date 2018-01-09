<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class LoginController extends AbstractController{
    /**
     * @Route("/login", name="login")
     */
    public function login(AuthenticationUtils $auth){
        $erreur = $auth->getLastAuthenticationError();
        $lastUserName = $auth->getLastUsername();
        $data = range(0, 9) + array_fill_keys(range(0,9), '');
        shuffle($data);
        $alea = array_chunk($data, 5, true);
        return $this->render('login/login.html.twig', array(
            'last_username' => $lastUserName, 'error' =>$erreur, 'table' => $alea
        ));
        
    }
}
