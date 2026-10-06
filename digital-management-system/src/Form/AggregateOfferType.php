<?php

/*
 * This file is part of the Graines Digitales DMS project.
 *
 * (c) Johan REMY <johan.remy@graines-digitales.online>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


/**
 * ...
 *
 * @author Johan REMY <johan.remy@graines-digitales.online>
 */
class AggregateOfferType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('price')
            ->add('availabilityStart', null,
                [
                    'label' => 'Start',
                    'widget' => 'single_text',
                    'format' => 'dd/MM/yyyy HH:mm',
                    'attr' => [
                      'class' => 'datetimepicker',
                    ],
                ]
            )
            ->add('availabilityEnd', null,
                [
                    'label' => 'End',
                    'widget' => 'single_text',
                    'format' => 'dd/MM/yyyy HH:mm',
                    'attr' => [
                      'class' => 'datetimepicker',
                    ],
                ]
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'App\Entity\AggregateOffer',
            'allow_extra_fields' => true,
        ]);
    }
}
