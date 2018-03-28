<?php

namespace AppBundle\Form;

use AppBundle\Entity\Venue;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class ChooseVenueType
 * @package AppBundle\Form
 */
class ChooseVenueType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('venues', EntityType::class, array(
                'class' => Venue::class,
                'multiple' => false,
                'expanded' => true
            ))
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_choosevenue';
    }
}
