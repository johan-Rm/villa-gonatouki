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

use App\Entity\Message;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


/**
 * ...
 *
 * @author Johan REMY <johan.remy@graines-digitales.online>
 */
class MessageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('dateSent')
            ->add('name')
            ->add('description')
            // ->add('url')
            // ->add('mainEntityOfPage')
            // ->add('headline')
            // ->add('alternativeHeadline')
            // ->add('text')
            // ->add('keywords')
            // ->add('datePublished')
            // ->add('expire')
            // ->add('dateCreated')
            // ->add('dateModified')
            // ->add('recipient')
            // ->add('sender')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Message::class,
            'form_name' => 'MessageType',
        ]);
    }
}
