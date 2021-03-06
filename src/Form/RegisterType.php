<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use \Symfony\Component\Form\Extension\Core\Type\PasswordType;

class RegisterType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder->add('Numero', IntegerType::class, array('label' => 'Numéro','attr' => array('class' => 'form-control', 'style' => "background-color: white", "placeholder" => "0000")))
                ->add('Nom', TextType::class, array('attr' => array('class' => 'form-control', 'style' => "background-color: white", "placeholder" => "Nom")))
                ->add('Prenom', TextType::class, array('label' => 'Prénom','attr' => array('class' => 'form-control', 'style' => "background-color: white", "placeholder" => "Prénom")))
                ->add('Codeperso', IntegerType::class, array('label' => 'Code Personnel', 'attr' => array('class' => 'form-control', 'style' => "background-color: white", "placeholder" => "0000")))
                ->add('Save', SubmitType::class, array('label' => 'Valider', 'attr' => array('class' => 'btn btn-secondary')));
        
    }

    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults([
        'data_class' => \App\Entity\Utilisateurs::class,
        ]);
    }

}
