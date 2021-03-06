<?php

namespace App\Form\AdherentMessage;

use App\Entity\AdherentMessage\Filter\CommitteeFilter;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CommitteeFilterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('ageMin', IntegerType::class, ['required' => false])
            ->add('ageMax', IntegerType::class, ['required' => false])
            ->add('firstName', TextType::class, ['required' => false])
            ->add('city', TextType::class, ['required' => false])
            ->add('registeredSince', DateType::class, ['required' => false, 'widget' => 'single_text', 'html5' => true])
            ->add('registeredUntil', DateType::class, ['required' => false, 'widget' => 'single_text', 'html5' => true])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => CommitteeFilter::class,
        ]);
    }
}
