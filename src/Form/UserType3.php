<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType3 extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('lastname', TextType::class, [

                'required' => false,
                'attr' => [
                    'placeholder' => 'Lastname'
                ]
            ])
            ->add('firstname', TextType::class, [

                'required' => false,
                'attr' => [
                    'placeholder' => 'Firstname'
                ]
            ])
            ->add('gender', ChoiceType::class, [
                'choices' => $this->getChoices3(),

                'attr' => [
                    'placeholder' => 'Gender'
                ]
             ])
            ->add('city', TextType::class, [

                'required' => false,
                'attr' => [
                    'placeholder' => 'City'
                ]
            ])
            ->add('address', TextType::class, [

                'required' => false,
                'attr' => [
                    'placeholder' => 'Address'
                ]
            ])
            ->add('postalCode', IntegerType::class, [

                'required' => false,
                'attr' => [
                    'placeholder' => 'Postal code'
                ]
            ])
            ->add('country', CountryType::class, [

                'required' => false,
                'attr' => [
                    'placeholder' => 'Country'
                ]
            ])
            ->add('phone', TextType::class, [

                'required' => false,
                'attr' => [
                    'placeholder' => 'Phone'
                ]
            ])
            ->add('mobile', TextType::class, [

                'required' => false,
                'attr' => [
                    'placeholder' => 'Mobile'
                ]
            ])
            ->add('job', TextType::class, [

                'required' => false,
                'attr' => [
                    'placeholder' => 'Job'
                ]
            ])
            ->add('biography', TextareaType::class, [

                'required' => false,
                'attr' => [
                    'style' => 'height: 150px;',
                    'maxlength' => '200'
                ]
            ])
            ->add('birthday', BirthdayType::class, [

                'required' => false,
                'placeholder' => 'Selected value',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'translation_domain' => 'forms'
        ]);
    }

    private function getChoices3()
    {
        $choices = USER::GENDER;
        $output = [];
        foreach($choices as $k => $v)
        {
            $output[$v] = $k;
        }
        return $output;
    }
}
