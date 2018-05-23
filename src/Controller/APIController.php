<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use App\Entity\Sagas;
use App\Entity\Livres;
use App\Entity\Utilisateurs;
use App\Entity\Commandes;
use App\Entity\Statut;
use App\Entity\ContenuCommandes;
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
     * @Route("/apiUser/{id}", name="un_user")
     */
    public function apiUser($id, EntityManagerInterface $em) {
        $unUser = $em->getRepository(Utilisateurs::class)->findBy([
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
     * @Route("/apiAllBook", name="all_book")
     */
    public function apiAllBook(EntityManagerInterface $em) {
        $uneSaga = $em->getRepository(Sagas::class)->findAll();
        $unLivre = $em->getRepository(Livres::class)->findAll();
        $serializer = $this->get('serializer');
        $data = $serializer->serialize($unLivre, 'json');
        $response = new Response($data);
        $response->headers->set('Content-Type', 'application/json');
        $response->headers->set('Ok', 'Oui');
        return $response;
    }

    /**
     * @Route("/apiLivresSagas/{titre}", name="produits_categorie")
     */
    public function apiGetLivresSagas($titre, EntityManagerInterface $em) {
        $id = urldecode($titre);
        $serializer = $this->get('serializer');
        $maSaga = $em->getRepository(Sagas::class)->findBy([
            'titreSaga' => $id
        ]);
        $lesLivres = $em->getRepository(Livres::class)->findBy([
            'sagaLivres' => $maSaga
        ]);
        $data = $serializer->serialize($lesLivres, 'json');
        $response = new Response($data);
        $response->headers->set('Content-Type', 'application/json');
        $response->headers->set('Ok', 'Oui');
        return $response;
    }
    

    /**
     * @Route("/apiUnContenu/{id}", name="get_un_contenu")
     */
    public function apiGetUnContenu($id, EntityManagerInterface $em) {
        $serializer = $this->get('serializer');
        $leContenu = $em->getRepository(ContenuCommandes::class)->findBy([
            'idcommande' => $id
        ]);
      //  dump($leContenu);
        foreach ($leContenu as $unC) {
            $unC->getIdcommande()->setNumeroutilisateur(null);
        }

        //$leContenu[0]->setNumeroUtilisateur(null);
        $data = $serializer->serialize($leContenu, 'json');
        $response = new Response($data);
        $response->headers->set('Content-Type', 'application/json');
        $response->headers->set('Ok', 'Oui');
        return $response;
    }
    

    /**
     * @Route("/ajoutsaga/",name="ajout_saga",methods="post")
     */
    public function ajoutSaga(Request $request, EntityManagerInterface $em) {
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

    /**
     * @Route("/apiStats",name="getStats")
     */
    public function apiStats(Request $request, EntityManagerInterface $em) {
        $serializer = $this->get('serializer');
        $utilisateurs = $this->getDoctrine()
                ->getRepository(Utilisateurs::class)
                ->findBy([
            'role' => 'ROLE_USER'
        ]);
        if (!$utilisateurs) {
            throw $this->createNotFoundException(
                    'Aucun client n\'est disponible'
            );
        }

        $produits = $this->getDoctrine()
                ->getRepository(Livres::class)
                ->findAll();
        if (!$produits) {
            throw $this->createNotFoundException(
                    'Aucun livre n\'est disponible'
            );
        }

        $queryBuilder = $em->createQueryBuilder();
        $queryBuilder
                ->from(Commandes::class, 'c')
                ->select('SUM(c.prixtotal)')
                ->where('c.statut != 4');
        $exe = $queryBuilder->getQuery();
        $results = $exe->execute();
        $chiffre = (String) $results[0][1];


        $query2 = $em->createQueryBuilder();
        $query2
                ->from(Commandes::class, 'c')
                ->select('COUNT(c.idcommande)')
                ->where('c.statut != 4');
        $exec = $query2->getQuery();
        $nbCommande = $exec->execute();
        $nbCommandes = (String) $nbCommande[0][1];


        $nbUtilisateurs = sizeof($utilisateurs);
        $nbProduits = sizeof($produits);

        $array = array(
            "nbClients" => $nbUtilisateurs,
            "nbLivres" => $nbProduits,
            "nbCommandesVal" => intval($nbCommandes),
            "CA" => round($chiffre, 2)
        );

        $data = $serializer->serialize($array, 'json');
        $response = new Response($data);
        $response->headers->set('Content-Type', 'application/json');
        $response->headers->set('Ok', 'Oui');
        return $response;
    }
    
    /**
     * @Route("/validerPanier",name="valider_panier",methods="post")
     */
    public function validerPanier(Request $request, EntityManagerInterface $em) {
        $obj = json_decode($request->getContent(), true);
        $utilisateur = $obj["numero_utilisateur"];
        $prixTotal = $obj['prix_total'];
        $arrayLivres = $obj['livres'];
        
        $commande = new Commandes();
        $statut = 4;
            $leStatut = $this->getDoctrine()
                    ->getRepository(Statut::class)
                    ->find($statut);
            if (!$leStatut) {
                throw $this->createNotFoundException(
                        'Ce statut n\'est pas disponible'
                );
            }
            $leUtilisateur = $this->getDoctrine()
                    ->getRepository(Utilisateurs::class)
                    ->find($utilisateur);
            if (!$leUtilisateur) {
                throw $this->createNotFoundException(
                        'Ce statut n\'est pas disponible'
                );
            }
            date_default_timezone_set('Europe/Paris');
            $commande->setDate(date("d-m-Y H:i:s"));
            $commande->setNumeroutilisateur($leUtilisateur);
            $commande->setPrixtotal($prixTotal);
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
            
            foreach ($arrayLivres as $idLivre => $qteLivre){
                $contenu = new ContenuCommandes();
                    $contenu->setIdcommande($laCommande);
                    $leLivre = $this->getDoctrine()
                            ->getRepository(Livres::class)
                            ->findOneBy([
                        'idLivre' => $idLivre
                    ]);
                    if (!$leLivre) {
                        throw $this->createNotFoundException(
                                'Ce livre n\'est pas disponible'
                        );
                    }
                    $stock = $leLivre->getStockLivre();
                    $quantite = $qteLivre;
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
            
            

        $response = new Response("{'test':'ioio'}");
        $response->headers->set('Content-Type', 'application/json');
        $response->headers->set('Ok', 'Oui');
        return $response;
    }
    

    /**
     * @Route("/updatesaga",name="update_saga",methods="post")
     */
    public function updateSaga(Request $request, EntityManagerInterface $em) {
        $obj = json_decode($request->getContent(), true);
        $titre = $obj["titre_saga"];
        $auteur = $obj['auteur_saga'];
        $nbLivre = (int) $obj["nb_livres_saga"];

        $connect = $em->getConnection();
        $connect->exec('UPDATE sagas SET auteur_saga = "' . $auteur . '", nb_livres_saga = ' . $nbLivre . ' WHERE titre_saga = "' . $titre . '"');

        $response = new Response("{'test':'ioio'}");
        $response->headers->set('Content-Type', 'application/json');
        $response->headers->set('Ok', 'Oui');
        return $response;
    }

    /**
     * @Route("/supplivre",name="supp_livre",methods="post")
     */
    public function suppLivre(Request $request, EntityManagerInterface $em) {
        $obj = json_decode($request->getContent(), true);
        $id = $obj["id_livre"];

        $connect = $em->getConnection();
        $connect->exec('DELETE FROM livres WHERE id_livre = ' . $id);

        $response = new Response("{'test':'ioio'}");
        $response->headers->set('Content-Type', 'application/json');
        $response->headers->set('Ok', 'Oui');
        return $response;
    }

    /**
     * @Route("/suppsaga",name="supp_saga",methods="post")
     */
    public function suppSaga(Request $request, EntityManagerInterface $em) {
        $obj = json_decode($request->getContent(), true);
        $titre = $obj["titre_saga"];

        $connect = $em->getConnection();
        $connect->exec('DELETE l.* FROM livres l INNER JOIN sagas s ON l.saga_livres = s.titre_saga WHERE l.saga_livres = "' . $titre . '"');
        $connect->exec('DELETE FROM sagas WHERE titre_saga = "' . $titre . '"');

        $response = new Response("{'test':'yes'}");
        return $response;
    }

    /**
     * @Route("/getCommandes/{num}",name="get_commandes")
     */
    public function getCommandes($num, EntityManagerInterface $em) {
        $serializer = $this->get('serializer');

        $queryBuilder = $em->createQueryBuilder();
        $queryBuilder
                ->from(Commandes::class, 'c')
                ->select('COUNT(c.idcommande)')
                ->where('c.numeroutilisateur =' . $num);
        $exe = $queryBuilder->getQuery();
        $results = $exe->execute();
        $nbCommandes = (String) $results[0][1];


        $query2 = $em->createQueryBuilder();
        $query2
                ->from(Commandes::class, 'm')
                ->select('m.idcommande')
                ->where('m.numeroutilisateur =' . $num);
        $exec = $query2->getQuery();
        $lesCommandes = $exec->execute();

        $lesContenus = array(array(
                "nbCommandes" => $nbCommandes,
        ));

        foreach ($lesCommandes as $uneCommande) {
            $query3 = $em->createQueryBuilder();
            $query3
                    ->from(ContenuCommandes::class, 'n')
                    ->select('n.idcontenu')
                    ->where('n.idcommande =' . $uneCommande["idcommande"]);

            $exect = $query3->getQuery();
            $leContenus = $exect->execute();
            $leContenu = array(
                $uneCommande["idcommande"] => array($leContenus),
            );
            $laCommande = array(
                0 => array(
                    "idCommande" => $uneCommande["idcommande"]
            ));
            $lesContenus = array_merge($lesContenus, $laCommande);
            $lesContenus = array_merge($lesContenus, $leContenu);
        }
        $data = $serializer->serialize($lesContenus, 'json');
        $response = new Response($data);
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

}
