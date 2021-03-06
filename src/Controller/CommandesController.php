<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Livres;
use App\Entity\Commandes;
use App\Entity\Statut;
use App\Entity\Utilisateurs;
use App\Entity\ContenuCommandes;

class CommandesController extends AbstractController {

    /**
     * @Route("/commandesNew/{numero}", name="NewCommandes")
     * @return Response
     */
    public function commandesNew(EntityManagerInterface $em) {

  //      $address_city = $_POST["adress_city"];
//        $address_city = $_POST["address_city"];
//        $address_country = $_POST["address_country"];
//        $address_street = $_POST["address_street"];
        
      //  dump($address_city);
        
        //die;

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

        $commande = new Commandes();

        if (isset($_SESSION['panier']['produit'])) {
            $prix = $_SESSION['panier']['prixTotal'];
            $statut = 4;
            $leStatut = $this->getDoctrine()
                    ->getRepository(Statut::class)
                    ->find($statut);
            if (!$leStatut) {
                throw $this->createNotFoundException(
                        'Ce statut n\'est pas disponible'
                );
            }
            date_default_timezone_set('Europe/Paris');
            $commande->setDate(date("d-m-Y H:i:s"));
            $commande->setNumeroutilisateur($leNumero);
            $commande->setPrixtotal($prix);
            $commande->setStatut($leStatut);

            $em->persist($commande);
            $em->flush();

            $idCommande = $commande->getIdcommande();


            $laCommande = $this->getDoctrine()
                    ->getRepository(Commandes::class)
                    ->find($idCommande);
            if (!$laCommande) {
                throw $this->createNotFoundException(
                        'Cette commande n\'est pas disponible'
                );
            }


            foreach ($_SESSION['panier']['produit'] as $livre => $id) {
                if (isset($_SESSION['panier']['produit'][$livre])) {

                    $contenu = new ContenuCommandes();
                    $contenu->setIdcommande($laCommande);
                    $leLivre = $this->getDoctrine()
                            ->getRepository(Livres::class)
                            ->findOneBy([
                        'idLivre' => $livre
                    ]);
                    if (!$leLivre) {
                        throw $this->createNotFoundException(
                                'Ce livre n\'est pas disponible'
                        );
                    }
                    $stock = $leLivre->getStockLivre();
                    $quantite = $_SESSION['panier']['produit'][$livre]['qte'];
                    $nouveauStock = $stock - $quantite;
                    if ($nouveauStock >= 0) {
                        $contenu->setIdlivre($leLivre);
                        $contenu->setNbLivre($quantite);
                        $em->persist($contenu);
                        $em->flush();

                        $leLivre->setStockLivre($nouveauStock);
                        $em->persist($leLivre);
                        $em->flush();
                    }
                }
            }

            unset($_SESSION['panier']);
            return $this->redirectToRoute("panier");
        }
        $livres = 'Ooups';
        $taille = 2;
        $message = 'Une erreur est survenue, veuillez recommencer.';
        return $this->render('panier/panier.html.twig', array('livres' => $livres, 'livresPanier' => 1, 'nbLivres' => $taille, 'message' => $message));
    }

    /**
     * @Route("/commandes/{numero}", name="Commandes{numero}")
     * @return Response
     */
    public function commandes(EntityManagerInterface $em) {

        $url = $_SERVER['REQUEST_URI'];
        $size = strlen($url);
        $numero = substr($url, 11, $size);
        $leNumero = $this->getDoctrine()
                ->getRepository(Utilisateurs::class)
                ->find($numero);
        if (!$leNumero) {
            throw $this->createNotFoundException(
                    'Cet utilisateur n\'est pas disponible'
            );
        }

        $commandes = $this->getDoctrine()
                ->getRepository(Commandes::class)
                ->findBy([
            'numeroutilisateur' => $leNumero->getNumero()
        ]);
        ;
        if (!$commandes) {
            throw $this->createNotFoundException(
                    'Aucune commande n\'est disponible'
            );
        }


        $taille = sizeof($commandes);

        foreach ($commandes as $uneCommande) {
            $contenu = $this->getDoctrine()
                    ->getRepository(ContenuCommandes::class)
                    ->findBy([
                'idcommande' => $uneCommande->getIdCommande()
            ]);
            if (!$contenu) {
                throw $this->createNotFoundException(
                        'Aucun contenu n\'est disponible'
                );
            }
            $uneCommande->leContenu($contenu);
            $size = sizeof($uneCommande->recupContenu());
            $uneCommande->laTaille($size);
        }
        $commandes = array_reverse($commandes);
        return $this->render('commandes/commandes.html.twig', array('commandes' => $commandes, 'nbLivres' => $taille));
    }

}
