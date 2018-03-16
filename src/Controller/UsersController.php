<?php

namespace App\Controller;

use App\Entity\Utilisateurs;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class UsersController extends AbstractController{
    /**
     * @Route("/usersclassique/{id}", name="un_user_cla")
     */
    public function apiUsersMethodeClassique($id, EntityManagerInterface $em){
        $unUser =$em->getRepository(Utilisateurs::class)->findBy([
                    'numero' => $id
                ]);
        $serializer = $this->get('serializer');
        $data = $serializer->serialize($unUser[0], 'json');
        $response = new Response($data);
        $response->headers->set('Content-Type', 'application/json');
        $response->headers->set('Ok', 'Oui');
        return $response;
    }
    
    /**
     * @Route("/usersautomatique/{id}", name="un_user_auto")
     */
    public function apiUsersMethodeAutomatique(Utilisateurs $unUser = null){
        if($unUser === null){
            $response = new Response($unUser);
            $response->headers->set('Ok', 'Non');
            $response->headers->setStatusCode(404);
            return $response;
        }
        $serializer = $this->get('serializer');
        $data = $serializer->serialize($unUser, 'json');
        $response = new Response($data);
        $response->headers->set('Content-Type', 'application/json');
        $response->headers->set('Ok', 'Oui');
        return $response;
    }
}
