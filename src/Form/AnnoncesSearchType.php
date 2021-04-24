<?php

namespace App\Form;

use App\Entity\Annonces;
use App\Entity\AnnoncesSearch;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AnnoncesSearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, [
                'required' => false,
                'label' => false,
                'attr' => [
                    'placeholder' => 'Rechercher par titre'
                ]
            ])
            ->add('category', ChoiceType::class, [
                'choices' => $this->getCat(),
                'required' => true,
                'label' => false,
                'attr' => [
                    'style' => 'text-align: center;'
                ],
            ])
            ->add('sousCategory', ChoiceType::class, [
                'choices' => $this->getCat2(),
                'label' => false,
                'required' => true,
                'attr' => [
                    'style' => 'text-align: center;'
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => AnnoncesSearch::class,
            'translation_domain' => 'forms',
            'method' => 'get',
            'csrf_protection' => false
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
