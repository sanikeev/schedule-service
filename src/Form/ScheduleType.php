<?php

namespace App\Form;

use App\DTO\ScheduleDTO;
use App\Entity\City;
use App\Entity\Courier;
use App\Entity\Schedule;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ScheduleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('city', EntityType::class, [
                'class' => City::class
            ])
            ->add('courier', EntityType::class, [
                'class' => Courier::class
            ])
            ->add('started_at', DateType::class, [
                'property_path' => 'startedAt',
                'widget' => 'single_text',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ScheduleDTO::class
        ]);
    }
}
