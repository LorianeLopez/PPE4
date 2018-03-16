<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\Sagas;
use App\Entity\Livres;
use App\Entity\Commandes;
use App\Entity\Statut;
use App\Entity\Utilisateurs;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Common\Collections\Collection;

class CommandesController extends AbstractController {

    /**
     * @Route("/commandesNew/{numero}", name="NewCommandes")
     * @return Response
     */
    public function commandes(\Doctrine\ORM\EntityManagerInterface $em) {

        $url = $_SERVER['REQUEST_URI'];
        $size = strlen($url);
        $numero = substr($url, 14, $size);
        $leNumero = $this->getDoctrine()
                ->getRepository(Utilisateurs::class)
                ->find($numero);
        if (!$leNumero) {
            throw $this->createNotFoundException(
                    'Cet utilisateur n\'est pas disponible'
            );
        }

        $livres = array();

        $commande = new Commandes();

        if (isset($_SESSION['panier']['produit'])) {
            $prix = $_SESSION['panier']['produit']['prixTotal'];
            $statut = 1;
            $leStatut = $this->getDoctrine()
                    ->getRepository(Statut::class)
                    ->find($statut);
            if (!$leStatut) {
                throw $this->createNotFoundException(
                        'Ce statut n\'est pas disponible'
                );
            }

            foreach ($_SESSION['panier']['produit'] as $livre => $id) {
                $donnees = array();
                $leLivre = $this->getDoctrine()
                        ->getRepository(Livres::class)
                        ->find($livre);
                if (!$leStatut) {
                    throw $this->createNotFoundException(
                            'Ce livre n\'est pas disponible'
                    );
                }
                $quantite = $_SESSION['panier']['produit'][$livre]['qte'];
                array_push($donnees, $leLivre);
                array_push($donnees, $quantite);
                array_push($livres, $donnees);
            }
            $commande->setIdlivre($livres);
            $commande->setNumeroutilisateur($leNumero);
            $commande->setPrixtotal($prix);
            $commande->setStatut($leStatut);
            $em->persist($commande);
            $em->flush();
//            unset($_SESSION['panier']['produit']);
//            return $this->redirectToRoute("commandes");
        }

//        return $this->render('produits/allBook.html.twig', array('sagas' => $sagas, 'livres' => $livres, 'nbLivres' => $taille));
    }

}
