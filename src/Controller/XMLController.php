<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

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
    public function export() {
        
        
        
        die;
        
        
    }

}
