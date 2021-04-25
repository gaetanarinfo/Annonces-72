<?php

namespace App\Form;

use App\Entity\Annonces;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AnnoncesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, [
                'required' => true,
                'label' => 'Quel est le titre de l’annonce ?'
            ])
            ->add('price', NumberType::class, [
                'required' => true,
                'label' => 'Quel est le prix de l’annonce ?',
            ])
            ->add('phone', NumberType::class, [
                'required' => true,
                'label' => 'Quel est votre numéro ?',
                'attr' => [
                    'maxlength' => '10'
                ]
            ])
            ->add('category', ChoiceType::class, [
                'required' => true,
                'choices' => $this->getCat(),
                'label' => 'Choisissez une catégorie ?',
            ])
            ->add('sousCategory', ChoiceType::class, [
                'required' => true,
                'choices' => $this->getCat2(),
                'label' => 'Choisissez une catégorie ?',
            ])
            ->add('smallContent', TextareaType::class, [
                'required' => true,
                'label' => 'Choisissez une petite description (120 caractères max) ?'
            ])
            ->add('largeContent', TextareaType::class, [
                'required' => true,
                'label' => 'Choisissez une grande description ?'
            ])
            ->add('criteres', TextareaType::class, [
                'required' => true,
                'label' => 'Choisissez des critères ?'
            ])
            ->add('pictureFiles', FileType::class, [
                'required' => true,
                'multiple' => true,
                'label' => 'Ajoutez des photos'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Annonces::class,
            'translation_domain' => 'forms'
        ]);
    }

    public function getBlockPrefix()
    {
        return '';
    }

    private function getCat()
    {
        $choices = Annonces::CAT_PRIMARY;
        $output = [];
        foreach($choices as $k => $v)
        {
            $output[$v] = $k;
        }

        return $output;
    }

    private function getCat2()
    {
        $choices = Annonces::CAT_VACANCES;
        $output = [];
        foreach($choices as $k => $v)
        {
            $output[$v] = $k;
        }

        return $output;
    }

}
