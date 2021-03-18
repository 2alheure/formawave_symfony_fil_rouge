<?php

namespace App\Form\Type;

use App\Entity\Project;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProjectType extends AbstractType {
    public function buildForm(FormBuilderInterface $formBuilder, array $options) {
        $formBuilder
            ->add('name', TextType::class, [
                'label' => 'Nom',
            ])
            ->add('description', TextareaType::class, [
                'required' => false,
                'label' => 'Courte description'
            ])
            ->add('image', UrlType::class, [
                'required' => false
            ])
            ->add('url', UrlType::class, [
                'required' => false
            ])
            ->add('date', DateType::class, [
                'format' => 'dd/MM/yyyy',
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Créer un projet'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults([
            'data_class' => Project::class
        ]);
    }
}
