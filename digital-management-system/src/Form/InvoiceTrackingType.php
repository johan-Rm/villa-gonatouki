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
class InvoiceTrackingType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name')
          ->add('invoiceDate', null, [
              'widget' => 'single_text',
              'format' => 'dd/MM/yyyy HH:mm',
              'attr' => [
                  'class' => 'datetimepicker',
              ],
            ]
            )
          ->add('paymentDate', null, [
              'widget' => 'single_text',
              'format' => 'dd/MM/yyyy HH:mm',
              'attr' => [
                  'class' => 'datetimepicker',
              ],
            ]
          )
          ->add('paymentMethod')
          ->add('amount')
          ->add('comment')
          ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'App\Entity\InvoiceTracking',
            'allow_extra_fields' => true,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'app_invoicetracking';
    }
}
