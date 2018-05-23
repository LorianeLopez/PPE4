<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\Sagas;
use App\Entity\Livres;

class PanierController extends AbstractController {

    /**
     * @Route("/panier/{idLivre}", name="panierLivre")
     * @return Response
     */
    public function panierLivre() {

        $_SERVER['REQUEST_URI'] = "http://127.0.0.1:86/panier/8";
        die;
        $url = $_SERVER['REQUEST_URI'];
        $size = strlen($url);
        $idLivre = substr($url, 8, $size);
        
        $livres = $this->getDoctrine()
                ->getRepository(Livres::class)
                ->find($idLivre);
        if (!$livres) {
            throw $this->createNotFoundException(
                    'Ce livre n\'est pas disponible'
            );
        }
        
        $stock = $livres->getStockLivre();
        if (isset($_SESSION['panier']['produit'][$idLivre])) {
                $quantite = $_SESSION['panier']['produit'][$idLivre]['qte'];
                $nouveauStock = $stock - ($quantite+1);
        }else{
            $nouveauStock = $stock;
        }
        
        if($nouveauStock >= 0){
            if (isset($_SESSION['panier']['produit'][$idLivre])) {
                $_SESSION['panier']['produit'][$idLivre]['qte'] ++;
            } else {
                $_SESSION['panier']['produit'][$idLivre] = array();
                $_SESSION['panier']['produit'][$idLivre]['qte'] = 1;
            }

            $taille = $_SESSION['panier']['produit'][$idLivre]['qte'];

            if (isset($_SESSION['panier']['prixTotal'])) {
                $_SESSION['panier']['prixTotal'] += $livres->getPrixLivre();
            } else {
                $_SESSION['panier']['prixTotal'] = $livres->getPrixLivre();
            }

            $message = 'Cet article a bien été ajouté au panier !';
        }else{
            $taille = 1;
            $message = 'Le Stock est insuffisant';
        }

        return $this->render('panier/panier.html.twig', array('livres' => $livres, 'livresPanier' => 1, 'nbLivres' => $taille, 'message' => $message));
    }

    /**
     * @Route("/panier", name="panier")
     * @return Response
     */
    public function panier() {

        $allLivres = $this->getDoctrine()
                ->getRepository(Livres::class)
                ->findAll();
        if (!$allLivres) {
            throw $this->createNotFoundException(
                    'Aucune livre n\'est disponible'
            );
        }
        $taille = sizeof($allLivres);

        $livres = array();
        $quantite = array();
        if (isset($_SESSION['panier']['produit'])) {
            for ($i = 1; $i <= $taille; $i++) {
                if (isset($_SESSION['panier']['produit'][$i])) {
                    $quantite[$i] = array();
                    $quantite[$i]['quantite'] = $_SESSION['panier']['produit'][$i]['qte'];
                    $livre = $this->getDoctrine()
                            ->getRepository(Livres::class)
                            ->find($i);
                    if (!$livre) {
                        throw $this->createNotFoundException(
                                'Ce livre n\'est pas disponible'
                        );
                    }
                    array_push($livres, $livre);
                }
            }
        }
        $nombreLivre = sizeof($livres);
        $message = 'Bienvenue dans votre panier !';
        
        return $this->render('panier/panier.html.twig', array('livres' => $livres, 'livresPanier' => $nombreLivre, 'nbLivres' => $taille, 'quantites' => $quantite, 'message' => $message));
    }

    /**
     * @Route("/panierVider", name="panierVider")
     * @return Response
     */
    public function panierVider() {

        if (isset($_SESSION['panier']['produit'])) {
            unset($_SESSION['panier']['produit']);
            unset($_SESSION['panier']['prixTotal']);
        }

        $livres = 'OOps';
        $taille = 70;
        $message = 'Votre Panier est vide !';

        return $this->render('panier/panier.html.twig', array('livres' => $livres, 'livresPanier' => 1, 'nbLivres' => $taille, 'message' => $message));
    }

    /**
     * @Route("/panierRetirer{idLivre}", name="panierRetirer")
     * @return Response
     */
    public function panierRetirer() {

        $url = $_SERVER['REQUEST_URI'];
        $size = strlen($url);
        $idLivre = substr($url, 14, $size);

        $livre = $this->getDoctrine()
                ->getRepository(Livres::class)
                ->findOneBy([
                        'idLivre' => $idLivre
                    ]);
        if (!$livre) {
            throw $this->createNotFoundException(
                    'Ce livre n\'est pas disponible'
            );
        }

        if (isset($_SESSION['panier']['produit'][$idLivre])) {
            $quantite = $_SESSION['panier']['produit'][$idLivre]['qte'];
            $prix = $livre->getPrixLivre() * $quantite;
            $_SESSION['panier']['prixTotal'] -= $prix;
            unset($_SESSION['panier']['produit'][$idLivre]);
        }
        if ($_SESSION['panier']['produit'] == null) {
            unset($_SESSION['panier']['produit']);
            unset($_SESSION['panier']['prixTotal']);
        }

        $livres = 'OOps';
        $taille = 70;
        $message = 'Cet Article à bien été retiré !';

        return $this->render('panier/panier.html.twig', array('livres' => $livres, 'livresPanier' => 1, 'nbLivres' => $taille, 'message' => $message));
    }

    /**
     * @Route("/panierMoins{idLivre}", name="panierMoins")
     * @return Response
     */
    public function panierMoins() {
        $url = $_SERVER['REQUEST_URI'];
        $size = strlen($url);
        $idLivre = substr($url, 12, $size);
        
        $livre = $this->getDoctrine()
                ->getRepository(Livres::class)
                ->findOneBy([
                        'idLivre' => $idLivre
                    ]);
        if (!$livre) {
            throw $this->createNotFoundException(
                    'Ce livre n\'est pas disponible'
            );
        }

        if (isset($_SESSION['panier']['produit'][$idLivre])) {
            $_SESSION['panier']['produit'][$idLivre]['qte'] --;
            $_SESSION['panier']['prixTotal'] -= $livre->getPrixLivre();
        } else {
            $_SESSION['panier']['produit'][$idLivre]['qte'] = 0;
            $_SESSION['panier']['prixTotal'] -= $livre->getPrixLivre();
        }

        if ($_SESSION['panier']['produit'][$idLivre]['qte'] <= 0) {
            unset($_SESSION['panier']['produit'][$idLivre]);
        }
        if (empty($_SESSION["panier"]["produit"])) {
            unset($_SESSION['panier']['produit']);
            unset($_SESSION['panier']['prixTotal']);
        }

        $livres = 'Oops';
        $taille = 2;
        $message = 'Cet élément à été retiré !';


        return $this->render('panier/panier.html.twig', array('livres' => $livres, 'livresPanier' => 1, 'nbLivres' => $taille, 'message' => $message));
    }

    /**
     * @Route("/panierPlus{idLivre}", name="panierPlus")
     * @return Response
     */
    public function panierPlus() {
        $url = $_SERVER['REQUEST_URI'];
        $size = strlen($url);
        $idLivre = substr($url, 11, $size);
        
        $livre = $this->getDoctrine()
                ->getRepository(Livres::class)
                ->findOneBy([
                        'idLivre' => $idLivre
                    ]);
        if (!$livre) {
            throw $this->createNotFoundException(
                    'Ce livre n\'est pas disponible'
            );
        }
        
        $stock = $livre->getStockLivre();
        if (isset($_SESSION['panier']['produit'][$idLivre])) {
                $quantite = $_SESSION['panier']['produit'][$idLivre]['qte'];
                $nouveauStock = $stock - ($quantite+1);
        }
        
        if($nouveauStock >= 0){
            if (isset($_SESSION['panier']['produit'][$idLivre])) {
                $_SESSION['panier']['produit'][$idLivre]['qte'] ++;
                $_SESSION['panier']['prixTotal'] += $livre->getPrixLivre();
            } else {
                $_SESSION['panier']['produit'][$idLivre]['qte'] = 1;
                $_SESSION['panier']['prixTotal'] += $livre->getPrixLivre();
            }
            $message = 'Cet élément à été ajouté !';
        }else{
            $message = 'Le Stock n\'est pas suffisant';
        }
        
        $livres = 'Oops';
        $taille = 2;
        
        return $this->render('panier/panier.html.twig', array('livres' => $livres, 'livresPanier' => 1, 'nbLivres' => $taille, 'message' => $message));
    }

}
