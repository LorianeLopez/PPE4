<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\Sagas;

class AccueilController extends AbstractController{
    /**
     * @Route("/", name="accueil")
     * @return Response
     */
    public function accueil(){
        $sagas = $this->getDoctrine()
                ->getRepository(Sagas::class)
                ->findAll();
        if (!$sagas) {
            throw $this->createNotFoundException(
                    'Aucune saga n\'est disponible'
            );
        }
        
        return $this->render('accueil/accueil.html.twig', array('sagas' => $sagas));
    }
}

