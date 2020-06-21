<?php

namespace App\Form;

use App\Entity\Contact;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstname', TextType::class, array(
                'label'=> "Prénom",
                'help' => "John, alex..."
            ))
            ->add('lastname', TextType::class, array(
                'label'=> "Nom",
                'help' => "Dupont, Baltus..."
            ))
            ->add('mail', EmailType::class,array(
                'help'=> 'toto@domain.fr, toto2@domain.fr',
                'label' => 'Email'
            ))
            ->add('address', TextareaType::class, array(
                'label'=> "Adresse",
                'help' => "11 avenue de canteranne 33600 PESSAC ..."
            ))
            ->add('submit', SubmitType::class, array(
                'label'=> "Sauvegarder"
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Contact::class,
        ]);
    }
}
