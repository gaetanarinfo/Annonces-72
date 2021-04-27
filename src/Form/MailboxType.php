<?php

namespace App\Form;

use App\Entity\Mailbox;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MailboxType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('content', TextareaType::class, [
                'label' => false,
                'attr' => [
                    'class' => 'form-control col-md-12',
                    'style' => 'height: 200px;'
                ]
            ])
            ->add('author', HiddenType::class, [
                'label' => false,
            ])
            ->add('subject', HiddenType::class, [
                'label' => false,
            ])
            ->add('recipient', HiddenType::class, [
                'label' => false,
            ])
            ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Mailbox::class,
            'translation_domain' => 'forms'
        ]);
    }

}
