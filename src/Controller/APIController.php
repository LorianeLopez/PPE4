<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\EncoderFactory;
use Symfony\Component\Security\Core\Encoder\MessageDigestPasswordEncoder;
use Symfony\Component\Security\Core\Encoder\PasswordEncoderInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use App\Entity\Sagas;
use App\Entity\Livres;
use App\Entity\Utilisateurs;
use Doctrine\ORM\EntityManagerInterface;

class APIController extends Controller {

    /**
     * @Route("/apiUnProduit/{id}", name="un_produit")
     */
    public function apiUnProduitMethodeClassique($id, EntityManagerInterface $em) {
        $uneSaga = $em->getRepository(Sagas::class)->findBy([
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
    public function apiAllProduitMethodeClassique(EntityManagerInterface $em) {
        $uneSaga = $em->getRepository(Sagas::class)->findAll();
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
    public function apiProduitsCategorieMethodeClassique($id, EntityManagerInterface $em) {
        $serializer = $this->get('serializer');
        $maSaga = $em->getRepository(Sagas::class)->findBy([
            'titreSaga' => $id
        ]);
        $lesLivre = $em->getRepository(Livres::class)->findBy([
            'sagaLivres' => $maSaga
        ]);
        $data = $serializer->serialize($lesLivre, 'json');
        $response = new Response($data);
        $response->headers->set('Content-Type', 'application/json');
        $response->headers->set('Ok', 'Oui');
        return $response;
    }

    /**
     * @Route("/ajoutsaga/",name="ajout_saga",methods="post")
     */
    public function ajoutLivre(Request $request, EntityManagerInterface $em) {
        $serializer = $this->get('serializer');
        $uneSaga = $serializer->deserialize($request->getContent(), Sagas::class, 'json');

        $em->persist($uneSaga);
        $em->flush();

        $response = new Response("{'test':'ioio'}");
        $response->headers->set('Content-Type', 'application/json');
        $response->headers->set('Ok', 'Oui');
        return $response;
    }

    /**
     * @Route("/Utilisateurs",name="getUtilisateurs")
     */
    public function connect(Request $request, EntityManagerInterface $em) {
        $user = $em->getRepository(Utilisateurs::class)->findBy([
            'numero' => $request->get('_username')
        ]);
        $encoder = $this->get('security.password_encoder');
        $plainPassword = $request->get('_password');

        $match = $encoder->isPasswordValid($user[0], $plainPassword);

        if ($match) {
            $response = new Response("Sucess");
            $response->headers->set('Content-Type', 'application/text');
            $response->headers->set('Ok', 'Oui');
            return $response;
        } else {
            $response = new Response("Fail - Identifiant Incorrect");
            $response->headers->set('Content-Type', 'application/text');
            $response->headers->set('Ok', 'Non');
            return $response;
        }
    }

}
