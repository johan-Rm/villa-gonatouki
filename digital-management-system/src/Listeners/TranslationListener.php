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
// use Doctrine\ORM\Event\LifecycleEventArgs;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\EventDispatcher\GenericEvent;
use EasyCorp\Bundle\EasyAdminBundle\Event\EasyAdminEvents;
use App\Entity\Translation;

/**
 * ...
 *
 * @author Johan REMY <johan.remy@graines-digitales.online>
 */
class TranslationListener implements EventSubscriberInterface
{
    /**
    * @var EntityManagerInterface
    */
    private $orm;

    /**
    * @var ContainerInterface
    */
    private $container;

    /**
    * @var RequestStack
    */
    private $request;

    /**
    * @param ContainerInterface $container
    * @param EntityManagerInterface $orm
    * @param RequestStack $requestStack
    */
    public function __construct(ContainerInterface $container, EntityManagerInterface $orm, RequestStack $requestStack)
    {
        $this->orm = $orm;
        $this->container = $container;
        $this->request = $requestStack->getCurrentRequest();

    }

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return array(
            EasyAdminEvents::POST_PERSIST => 'translate',
            EasyAdminEvents::POST_UPDATE => 'translate',
        );
    }

    // public function postPersist(LifecycleEventArgs $args)
    // {
    //     $entity = $args->getEntity();
    //     if ($entity instanceof Translation) {
    //         return;
    //     }
    //     dump('translationListener postPersist');
    //     dump($entity);
    //     die();
    // }

    public function translate(GenericEvent $event)
    {
        $entity = $event->getArguments()['entity'];
        if ($entity instanceof Translation) {
            return;
        }
// die('translate');
            // $config = $this->container->get('easyadmin.config.manager')->getBackendConfig();
            // $serializer = $this->container->get('jms_serializer');
            // $className = $this->getClassName(get_class($entity));
            // $config = $config['entities'][$className];
            // $values = $serializer->toArray($entity);
      

            // $translator = $this->container->get('app.translation.easyadmin.translator');
            // $translator->setEntityIdentifier($entity->getId());
            // $translator->setEasyadminConfig($config);
            // $translator->process($values);
            // $translator->write();// à modifier, pourquoi faire cette manip lors des mises à jour du front
     


    }

    public function getClassName($class) {
        $path = explode('\\', $class);
        return array_pop($path);
    }

}
