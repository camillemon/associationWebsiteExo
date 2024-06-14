<?php

// src/Form/VolunteerFormType.php

namespace App\Form;

use App\Entity\Events;
use App\Entity\Volunteers;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VolunteerFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class)
            ->add('surname', TextType::class)
            ->add('email', EmailType::class)
            ->add('events', EntityType::class, [
                'class' => Events::class,
                'choice_label' => function (Events $event) {
                    return $event->getLocation() . ' - ' . $event->getDate()->format('d-m-Y');
                },
                'multiple' => true,
                'expanded' => true, // Utilisation de cases à cocher au lieu d'un menu déroulant
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Submit',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Volunteers::class,
        ]);
    }
}

