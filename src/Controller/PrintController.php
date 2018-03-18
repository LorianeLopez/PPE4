<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\Commandes;
use App\Entity\ContenuCommandes;

class PrintController extends AbstractController {

    /**
     * @Route("/print/{idCommande}", name="PrintCommande")
     */
    public function printAction() {

        $url = $_SERVER['REQUEST_URI'];
        $size = strlen($url);
        $idCommande = substr($url, 7, $size);

        $laCommande = $this->getDoctrine()
                ->getRepository(Commandes::class)
                ->findOneBy([
            'idcommande' => $idCommande
        ]);
        if (!$laCommande) {
            throw $this->createNotFoundException(
                    'Cette commande n\'est pas disponible'
            );
        }

        $statut = $laCommande->getStatut()->getLibellestatut();
        $nom = $laCommande->getNumeroutilisateur()->getNom();
        $prenom = $laCommande->getNumeroutilisateur()->getPrenom();
        $prixTotal = $laCommande->getPrixTotal();
        
        $contenu = $this->getDoctrine()
                    ->getRepository(ContenuCommandes::class)
                    ->findBy([
                        'idcommande' => $laCommande->getIdCommande()
                    ]);
            if (!$contenu) {
                throw $this->createNotFoundException(
                        'Aucun contenu n\'est disponible'
                );
            }
            
            $taille = sizeof($contenu);

        $pdf = new \FPDF();
        $pdf->AddPage();
        $pdf->SetTitle('Commande ' . $idCommande);
        
        $pdf->SetFont('Courier', 'B', 14);
        $pdf->SetTextColor(239, 96, 143);
        $pdf->Image("build/img/logo_bookstore.png", 30, null, 50, 50);
        $pdf->Text(43, 67, 'BookStore');
        
        $pdf->SetFont('Courier', '', 24);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->Text(110, 37, utf8_decode($nom. ' '.$prenom));
        $pdf->SetFont('Courier', 'BU', 16);
        $pdf->Text(120, 55, utf8_decode('Commande n°' . $idCommande));
        
        $pdf->SetFont('Courier', 'U', 12);
        $pdf->Text(10, 90, utf8_decode($statut));
        $pdf->SetFont('Courier', '', 12);
        $pdf->Text(10, 105, utf8_decode('Nombre d\'articles : '. $taille));
        $pdf->Text(10, 113, utf8_decode('Prix Total : '. $prixTotal . ' Euros'));
        
        $i = 130;
        $j = 135;
        $h = 155;
        foreach ($contenu as $unLivre){
            $titre = $unLivre->getIdLivre()->getTitreLivre();
            $img = $unLivre->getIdLivre()->getCouvertureLivre();
            $prix = $unLivre->getIdLivre()->getPrixLivre();
            $pdf->Image($img, 20, $i, 35, 40);
            $pdf->Text(65, $j, utf8_decode($titre));
            $pdf->Text(95, $h, utf8_decode($prix . ' Euros'));
            $i += 50;
            $j += 50;
            $h += 50;
        }
        
        return new Response($pdf->Output(), 200, array('Content-Type' => 'application/pdf'));
    }

}
