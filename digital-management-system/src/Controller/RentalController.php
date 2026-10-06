<?php

/*
 * This file is part of the Graines Digitales DMS project.
 *
 * (c) Johan REMY <johan.remy@graines-digitales.online>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Controller;

use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Response;


/**
 * ...
 *
 * @author Johan REMY <johan.remy@graines-digitales.online>
 */
class RentalController extends AbstractController
{
    /**
     * The method that is executed when the user performs a 'list' action on an entity.
     *
     * @return Response
     */
    protected function listRentalAction()
    {
        if (null !== $this->request->query->get('type')
          && 'full_calendar' == $this->request->query->get('type')
        ) {
            $this->entity['templates']['list'] = 'pages/appointment.html.twig';
        }

        return parent::listAction();
    }

    protected function createFiltersForm(string $entityName): FormInterface
    {
        $form = parent::createFiltersForm($entityName);
        $form->add('beginAt', DateType::class, [
              'widget' => 'single_text',
              'format' => 'dd/MM/yyyy HH:mm',
              'attr' => [
                  'class' => 'datetimepicker',
              ],
            ]
        );
        $form->add('endAt', DateType::class, [
              'widget' => 'single_text',
              'format' => 'dd/MM/yyyy HH:mm',
              'attr' => [
                  'class' => 'datetimepicker',
              ],
            ]
        );

        return $form;
    }
}
