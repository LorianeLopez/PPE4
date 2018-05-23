<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Utilisateurs;
use Doctrine\ORM\EntityManagerInterface;

class RegisterController extends AbstractController {

    /**
     * @Route("/register", name="register")
     * @param \App\Controller\Request $request
     * @return type
     */
    public function register(EntityManagerInterface $em, Request $request) {
        $tache = new Utilisateurs();
        $form = $this->createForm(\App\Form\RegisterType::class, $tache);
        try {
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $is_active = 1;
                $role = 'ROLE_USER';
                $tache->setIsActive($is_active);
                $tache->setRole($role);
                $em->persist($tache);
                $em->flush();
                return $this->redirectToRoute("login");
            }
            return $this->render('register/register.html.twig', array('form' => $form->CreateView(), "message" => ""));
        } catch (\Exception $error) {
            return $this->render('register/register.html.twig', array('form' => $form->CreateView(), "message" => "Ce Numéro d'Utilisateur est déjà pris. Veuillez en entrer un autre."));
        }
    }

}
