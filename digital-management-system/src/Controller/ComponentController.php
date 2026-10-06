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

use EasyCorp\Bundle\EasyAdminBundle\Event\EasyAdminEvents;


/**
 * ...
 *
 * @author Johan REMY <johan.remy@graines-digitales.online>
 */
class ComponentController extends AbstractController
{
    /**
     * The method that is executed when the user performs a 'edit' action on an entity.
     *
     * @return Response|RedirectResponse
     *
     * @throws \RuntimeException
     */
    protected function editInformationAction()
    {
        $this->entity['templates']['edit'] = 'pages/edit_setting.html.twig';

        return parent::editAction();
    }

    /**
     * The method that is executed when the user performs a 'edit' action on an entity.
     *
     * @return Response|RedirectResponse
     *
     * @throws \RuntimeException
     */
    protected function editGoogleAnalyticsAction()
    {
        $this->entity['templates']['edit'] = 'pages/edit_setting.html.twig';

        return parent::editAction();
    }

    /**
     * The method that is executed when the user performs a 'list' action on an entity.
     *
     * @return Response
     */
    protected function listMessageAction()
    {
        $this->entity['list']['dql_filter'] = 'entity.component =  1';
        $this->dispatch(EasyAdminEvents::PRE_LIST);

        $fields = $this->entity['list']['fields'];
        $paginator = $this->findAll($this->entity['class'], $this->request->query->get('page', 1), $this->entity['list']['max_results'], $this->request->query->get('sortField'), $this->request->query->get('sortDirection'), $this->entity['list']['dql_filter']);

        $this->dispatch(EasyAdminEvents::POST_LIST, ['paginator' => $paginator]);

        $parameters = [
            'paginator' => $paginator,
            'fields' => $fields,
            'delete_form_template' => $this->createDeleteForm($this->entity['name'], '__id__')->createView(),
        ];

        return $this->executeDynamicMethod('render<EntityName>Template', ['list', $this->entity['templates']['list'], $parameters]);
    }

    /**
     * The method that is executed when the user performs a 'list' action on an entity.
     *
     * @return Response
     */
    protected function listNewsletterAction()
    {
        $this->entity['list']['dql_filter'] = 'entity.component =  2';
        $this->dispatch(EasyAdminEvents::PRE_LIST);

        $fields = $this->entity['list']['fields'];
        $paginator = $this->findAll($this->entity['class'], $this->request->query->get('page', 1), $this->entity['list']['max_results'], $this->request->query->get('sortField'), $this->request->query->get('sortDirection'), $this->entity['list']['dql_filter']);

        $this->dispatch(EasyAdminEvents::POST_LIST, ['paginator' => $paginator]);

        $parameters = [
            'paginator' => $paginator,
            'fields' => $fields,
            'delete_form_template' => $this->createDeleteForm($this->entity['name'], '__id__')->createView(),
        ];

        return $this->executeDynamicMethod('render<EntityName>Template', ['list', $this->entity['templates']['list'], $parameters]);
    }
}
