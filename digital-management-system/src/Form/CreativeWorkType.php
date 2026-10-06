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

use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


/**
 * ...
 *
 * @author Johan REMY <johan.remy@graines-digitales.online>
 */
class CreativeWorkType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        // ->add('headline')
        ->add('alternativeHeadline', TextType::class, [
          'label' => 'title',
          // 'css_class' => 'not_compound',
          // 'attr' => array('css_class' => 'not_compound')
        ])
        ->add('text', CKEditorType::class)
        ->add('keyword')
        // ->add('dateCreated')
        // ->add('dateModified')
        // ->add('datePublished')
        // ->add('expire')
        ->add('thing', ThingType::class)
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'App\Entity\CreativeWork',
            'cascade_validation' => true,
            'allow_extra_fields' => true,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_creative_work';
    }
}
