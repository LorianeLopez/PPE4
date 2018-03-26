<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\Sagas;
use App\Entity\Livres;
use App\Entity\Utilisateurs;
use Doctrine\ORM\EntityManagerInterface;

class APIController extends AbstractController {
     /**
     * @Route("/apiUnProduit/{id}", name="un_produit")
     */
    public function apiUnProduitMethodeClassique($id, EntityManagerInterface $em){
        $uneSaga =$em->getRepository(Sagas::class)->findBy([
                    'titreSaga' => $id
                ]);
        $serializer = $this->get('serializer');
        $data = $serializer->serialize($uneSaga[0], 'json');
        $response = new Response($data);
        $response->headers->set('Content-Type', 'application/json');
        $response->headers->set('Ok', 'Oui');
        return $response;
    }
    
    /**
     * @Route("/apiAllProduit", name="all_produit")
     */
    public function apiAllProduitMethodeClassique(EntityManagerInterface $em){
        $uneSaga =$em->getRepository(Sagas::class)->findAll();
        $serializer = $this->get('serializer');
        $data = $serializer->serialize($uneSaga, 'json');
        $response = new Response($data);
        $response->headers->set('Content-Type', 'application/json');
        $response->headers->set('Ok', 'Oui');
        return $response;
    }
    
     /**
     * @Route("/apiProduitsCategorie/{id}", name="produits_categorie")
     */
    public function apiProduitsCategorieMethodeClassique($id, EntityManagerInterface $em){
        $serializer = $this->get('serializer');
        $maSaga =$em->getRepository(Sagas::class)->findBy([
                    'titreSaga' => $id
                ]);
        $lesLivre =$em->getRepository(Livres::class)->findBy([
                    'sagaLivres' => $maSaga
                ]);
        $data = $serializer->serialize($lesLivre, 'json');
        $response = new Response($data);
        $response->headers->set('Content-Type', 'application/json');
        $response->headers->set('Ok', 'Oui');
        return $response;
    }
    
    /**
     * @Route("/ajoutlivre/",name="ajout_livre",methods="post")
     */
    public function ajoutLivre(Request $request, EntityManagerInterface $em){
        $serializer = $this->get('serializer');
        $uneSaga = $serializer->deserialize($request->getContent(), Sagas::class, 'json');
        
        $em->persist($uneSaga);
        $em->flush();
        
        $response = new Response("L'ajout est réalisé !");
        $response->headers->set('Content-Type', 'application/json');
        $response->headers->set('Ok', 'Oui');
        return $response;
    }
}

