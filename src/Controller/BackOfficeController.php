<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\Livres;
use App\Entity\Commandes;
use App\Entity\Statut;
use App\Entity\Utilisateurs;
use App\Entity\ContenuCommandes;

class BackOfficeController extends AbstractController {

    /**
     * @Route("/backOffice", name="BackOffice")
     * @return Response
     */
    public function BackOfficeAction(\Doctrine\ORM\EntityManagerInterface $em) {
        return $this->redirectToRoute('easyadmin', array(
            'action' => 'show',
            'id' => 3,
            'entity' => $this->request->query->get('entity'),
        ));
    }
}

