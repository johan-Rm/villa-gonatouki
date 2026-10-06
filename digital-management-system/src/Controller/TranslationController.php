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

use Symfony\Component\Filesystem\Filesystem;

/**
 * ...
 *
 * @author Johan REMY <johan.remy@graines-digitales.online>
 */
class TranslationController extends AbstractController
{
    /**
     * The method that is executed when the user performs a 'list' action on an entity.
     *
     * @return Response
     */
    protected function listTranslationAction()
    {
        // die(' translation go!');

        return parent::listAction();
    }

    /**
     * Allows applications to modify the entity associated with the item being
     * created while persisting it.
     *
     * @param object $entity
     */
    protected function updateTranslationEntity($entity)
    {
        if (false == $entity->getIsHtml()) {
            $value = strip_tags($entity->getValueRight());
            $entity->setValueRight($value);
        }

        $this->em->flush();

        $translator = $this->container->get('app.translation.easyadmin.translator');
        $translator->write();

        $filesystem = new Filesystem();
        // dump($this->getParameter('kernel.cache_dir'));
        // die;
        $filesystem->remove($this->getParameter('kernel.cache_dir') . '/translations');
    }
}
