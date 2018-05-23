<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Port\Xml\XmlWriter;
use App\Entity\Commandes;
use Doctrine\ORM\EntityManagerInterface;

class XMLController extends AbstractController {

    /**
     * @Route("/xml", name="xml")
     * @return Response
     */
    public function import() {

//        $fichier = $_FILES['theFile']["tmp_name"];
//        
//         //On récupère le contenu du fichier XML source
//        $xml_source = file_get_contents($fichier);
//
//        // On retire les balises vides
//        $motif = "`<[a-zA-Z0-9_]*/>`i";
//        $xml_source_clean = preg_replace($motif, "", $xml_source);
//
//        // On met le code XML nettoyé des balises vides dans un fichier temporaire
//        file_put_contents("temp.xml", $xml_source_clean);
//       
//
//        // On importe tout ça dans la BDD
//        mysql_query("LOAD XML INFILE 'temp.xml'") or die("Erreur MySQL : " . mysql_error());
//
//        // On supprime le fichier temporaire
//        unlink("temp.xml");


        die;
    }

    /**
     * @Route("/exportXml", name="export")
     * @return Response
     */
    public function export(EntityManagerInterface $em) {

        $query = $em->createQueryBuilder();
        $query
                ->from(Commandes::class, 'c')
                ->select('*');
        $exec = $query->getQuery();
        $data = $exec->execute();
        $xml = new XMLWriter();
        $xml->openUri("commandes.xml");
        $xml->startDocument('1.0', 'utf-8');
        $xml->startElement('commandes');

        while ($pers = $data->fetch()) {
            $xml->startElement('commande');
            $xml->writeAttribute('idCommande', $pers['idcommande']);
            $xml->writeElement('numeroUtilisateur', $pers['numeroutilisateur']);
            $xml->writeElement('statut', $pers['statut']);
            $xml->writeElement('prixTotal', $pers['prixtotal']);
            $xml->writeElement('date', $pers['date']);
            $xml->endElement();
        }
        $xml->endElement();
        $xml->endElement();
        $xml->flush();

        die;
    }

}
