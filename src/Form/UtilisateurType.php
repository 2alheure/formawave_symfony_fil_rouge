<?php

namespace App\Form;

use App\Entity\Utilisateur;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UtilisateurType extends AbstractType {
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
            ->add('email')
            ->add('roles', ChoiceType::class, [
                'choices' => [
                    'Utilisateur simple' => 'ROLE_USER',
                    'Auteur' => 'ROLE_AUTEUR',
                    'Admin' => 'ROLE_ADMIN'
                ],
                'multiple' => true
            ])
            ->add('password', PasswordType::class)
            ->add('prenom')
            ->add('nom')
            ->add('dateDeNaissance')
            ->add('pseudo')
            ->add('isVerified');
    }

    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults([
            'data_class' => Utilisateur::class,
        ]);
    }
}
