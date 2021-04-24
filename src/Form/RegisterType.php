<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;

class RegisterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username', TextType::class, [
                'label' => false,
                'attr' => [ 
                    'placeholder' => 'Username'
                ]
            ])
            ->add('email', EmailType::class, [
                'label' => false,
                'attr' => [ 
                    'placeholder' => 'Email'
                ]
            ])
            ->add('password', RepeatedType::class, array(
                'type' => PasswordType::class,
                'label' => false,
                'first_options'
                 => array('label' => false, 
                 'attr' => [
                    'placeholder' => 'Password',
                    'maxlength' => '25'
                ]),
                'second_options' => array('label' => false, 'attr' => [
                    'placeholder' => 'Repeat Password',
                    'maxlength' => '25'
                ])))
            ->add('phone', TextType::class, [
                'label' => false,
                'attr' => [ 
                    'placeholder' => 'phone'
                ]
            ])                  
            ->add('mobile', TextType::class, [
                'label' => false,
                'attr' => [ 
                    'placeholder' => 'Mobile'
                ]
            ])    
            ->add('agreeTerms', CheckboxType::class, [
                'mapped' => false,
                'constraints' => new IsTrue(),
                'attr' => [
                    'class' => 'text-center'
                ]
                ])
            ->add('pictureFiles', FileType::class, [
                'label' => false,
                'multiple' => false,
                'required' => true,
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

}
