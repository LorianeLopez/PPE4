<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\Utilisateurs;

class Fixtures extends AbstractController{
    /**
     * @Route ("/fixtu", name="fixtu.utilisateurs")
     */
    public function ajoutUtilisateur(){
        $em = $this->getDoctrine()->getManager();
        $lesNouveaux = array("Loïc", "Samuel");
        $numeroLoic = 98752;
        $numeroSam = 147852;
        foreach ($lesNouveaux as $prenom){
            $utilisateur = new Utilisateurs();
            $utilisateur->setPrenom($prenom);
            $utilisateur->setIsActive(1);
            $utilisateur->setRole('ROLE_USER');
            if($prenom == "Loïc"){
                $utilisateur->setNom('Labede');
                $utilisateur->setNumero($numeroLoic);
                $utilisateur->setCodeperso(2222);
            }else{
                $utilisateur->setNom('Boulander');
                $utilisateur->setNumero($numeroSam);
                $utilisateur->setCodeperso(1111);
            }
            $em->persist($utilisateur);  
        }
        $em->flush();
        $e = $this->generateUrl("accueil", array(), true);
        return new Response('Ajout réussis ! <a href='.$e.'>Retour Accueil </a>');
    }
}

