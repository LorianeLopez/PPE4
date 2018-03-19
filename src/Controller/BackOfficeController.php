<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;
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
    public function BackOfficeAction(EntityManagerInterface $em) {

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
        $chiffre = (String)$results[0][1];
        
        
        $query2 = $em->createQueryBuilder();
        $query2
                ->from(Commandes::class, 'c')
                ->select('COUNT(c.idcommande)')
                ->where('c.statut != 4');
        $exec = $query2->getQuery();
        $nbCommande = $exec->execute(); 
        $nbCommandes = (String)$nbCommande[0][1];


        $nbUtilisateurs = sizeof($utilisateurs);
        $nbProduits = sizeof($produits);


        return $this->render('backoffice/backoffice.html.twig', array('NbUtilisateurs' => $nbUtilisateurs, 'NbLivres' => $nbProduits, 'NbCommandes' => $nbCommandes, 'chiffreAffaire' => $chiffre));
    }

}
