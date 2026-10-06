<?php

/*
 * This file is part of the Graines Digitales DMS project.
 *
 * (c) Johan REMY <johan.remy@graines-digitales.online>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Listeners;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * ...
 *
 * @author Johan REMY <johan.remy@graines-digitales.online>
 */
class AppListener
{
    private $orm;
    private $container;

    public function __construct(ContainerInterface $container, EntityManagerInterface $orm)
    {
        $this->orm = $orm;
        $this->container = $container;
    }

    public function postUpdate(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
    }
}
