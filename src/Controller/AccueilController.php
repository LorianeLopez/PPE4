<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AccueilController extends AbstractController{
    /**
     * @Route("/", name="accueil")
     * @return Response
     */
    public function accueil(){
        return $this->render('accueil/accueil.html.twig');
    }
}

