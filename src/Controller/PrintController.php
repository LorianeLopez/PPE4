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
        $date = $laCommande->getDate();

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
        $pdf->SetFont('Courier', 'B', 11);
        $pdf->SetTextColor(228, 116, 30);
        $pdf->Text(13, 75, '99, rue des Dunes - 75000 Paris');

        $pdf->SetFont('Courier', '', 24);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->Text(110, 37, utf8_decode($nom . ' ' . $prenom));
        $pdf->SetFont('Courier', 'BU', 16);
        $pdf->Text(120, 55, utf8_decode('Commande n°' . $idCommande));

        $pdf->SetFont('Courier', 'U', 12);
        $pdf->Text(10, 90, utf8_decode($statut));
        $pdf->SetFont('Courier', '', 12);
        $nbArticle = 0;
        foreach ($contenu as $unLivre) {
            $nbExemplaire = $unLivre->getNbLivre();
            $nbArticle += $nbExemplaire;
        }
        $pdf->Text(10, 100, utf8_decode('Nombre d\'articles : ' . $nbArticle));
        $pdf->Text(10, 107, utf8_decode('Prix Total : ' . $prixTotal . ' Euros'));
        $pdf->Text(10, 114, utf8_decode('Commande effectuée le : ' . $date));

        $i = 130;
        $j = 135;
        $h = 155;
        $m = 145;
        $ind = 0;
        foreach ($contenu as $unLivre) {
            $nbExemplaire = $unLivre->getNbLivre();
            if ($ind < 3) {
                $titre = $unLivre->getIdLivre()->getTitreLivre();
                $img = $unLivre->getIdLivre()->getCouvertureLivre();
                $prix = $unLivre->getIdLivre()->getPrixLivre();
                $pdf->Image($img, 20, $i, 35, 40);
                $pdf->Text(65, $j, utf8_decode($titre));
                if($nbExemplaire === 1){
                    $pdf->Text(65, $m, utf8_decode($nbExemplaire. " Exemplaire"));
                    $pdf->Text(95, $h, utf8_decode($prix . ' Euros'));
                }else{
                    $pdf->Text(65, $m, utf8_decode($nbExemplaire. " Exemplaires"));
                    $pdf->Text(83, $h, utf8_decode($prix . ' Euros / Unité'));
                    $pdf->Text(95, $h + 10, utf8_decode($prix * $nbExemplaire . ' Euros'));
                }
                $i += 50;
                $j += 50;
                $h += 50;
                $m += 50;
                $ind ++;
            } else {
                if ($ind === 3 || $ind == 5) {
                    $pdf->AddPage();
                    $i = 35;
                    $j = 38;
                    $m = 48;
                    $h = 58;
                    $ind = 0;
                }
                $titre = $unLivre->getIdLivre()->getTitreLivre();
                $img = $unLivre->getIdLivre()->getCouvertureLivre();
                $prix = $unLivre->getIdLivre()->getPrixLivre();
                $pdf->Image($img, 20, $i, 35, 40);
                $pdf->Text(65, $j, utf8_decode($titre));
                if($nbExemplaire === 1){
                    $pdf->Text(65, $m, utf8_decode($nbExemplaire. " Exemplaire"));
                    $pdf->Text(95, $h, utf8_decode($prix . ' Euros'));
                }else{
                    $pdf->Text(65, $m, utf8_decode($nbExemplaire. " Exemplaires"));
                    $pdf->Text(83, $h, utf8_decode($prix . ' Euros / Unité'));
                    $pdf->Text(95, $h + 10, utf8_decode($prix * $nbExemplaire . ' Euros'));
                }
                $i += 50;
                $j += 50;
                $h += 50;
                $m += 50;
            }
        }


        return new Response($pdf->Output(), 200, array('Content-Type' => 'application/pdf'));
    }

}
