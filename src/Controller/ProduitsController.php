<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\Sagas;
use App\Entity\Livres;
use Doctrine\ORM\EntityManagerInterface;

class ProduitsController extends AbstractController {

    /**
     * @Route("/produits", name="produits")
     * @return Response
     */
    public function produits() {

        $sagas = $this->getDoctrine()
                ->getRepository(Sagas::class)
                ->findAll();
        if (!$sagas) {
            throw $this->createNotFoundException(
                    'Aucune saga n\'est disponible'
            );
        }
        
        $livres = $this->getDoctrine()
                ->getRepository(Livres::class)
                ->findAll();
        if (!$livres) {
            throw $this->createNotFoundException(
                    'Aucune livre n\'est disponible'
            );
        }
        $taille = sizeof($livres);

        return $this->render('produits/allBook.html.twig', array('sagas' => $sagas, 'livres' => $livres, 'nbLivres' => $taille));
    }
    
    
    /**
     * @Route("/produits/{titreSaga}", name="produits{titreSaga}")
     * @return Response
     */
    public function produitsSaga() {
        $url = $_SERVER['REQUEST_URI'];
        $size = strlen($url);
        $saga_Livres = substr($url,10, $size);
        $sagaLivres = urldecode($saga_Livres);
        $sagas = $this->getDoctrine()
                ->getRepository(Sagas::class)
                ->findAll();
        if (!$sagas) {
            throw $this->createNotFoundException(
                    'Aucune saga n\'est disponible'
            );
        }
        
        $livres = $this->getDoctrine()
                ->getRepository(Livres::class)
                ->findBy([
                    'sagaLivres' => $sagaLivres
                ]);
        if (!$livres) {
            throw $this->createNotFoundException(
                    'Ces livres ne sont pas disponibles'
            );
        }
        
        $nblivres = $this->getDoctrine()
                ->getRepository(Livres::class)
                ->findAll();
        if (!$nblivres) {
            throw $this->createNotFoundException(
                    'Aucune livre n\'est disponible'
            );
        }
        $taille = sizeof($nblivres);
        

        return $this->render('produits/allBook.html.twig', array('sagas' => $sagas, 'livres' => $livres, 'nbLivres' => $taille));
    }
    
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

}
