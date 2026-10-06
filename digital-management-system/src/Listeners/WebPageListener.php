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
use App\Entity\WebPage;


/**
 * ...
 *
 * @author Johan REMY <johan.remy@graines-digitales.online>
 */
class WebPageListener
{
    /**
    * @var ContainerInterface
    */
    private $container;

    /**
    * @var SEO\MetaData
    */
    private $seoMetaDataService;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->seoMetaDataService = $this->container->get('app.seo.meta_data');
    }

    public function postRemove(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        if (!$entity instanceof WebPage) {
            return;
        }

        if ('cli' !== php_sapi_name()) {
            $nuxtJsRouter = $this->container->get('app.nuxtjs.router');
            $nuxtJsRouter->generate($entity, ['route' => 'web_pages']);

            $nuxtJsExportJsonData = $this->container->get('app.nuxtjs.export_json_data');
            $nuxtJsExportJsonData->generate($entity, ['route' => 'web_pages']);
        }
    }

    public function preUpdate(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();

        if (!$entity instanceof WebPage) {
            return;
        }

        if (empty($entity->getAlternativeHeadline())) {
            $entity->setAlternativeHeadline($entity->getHeadline());
        }

    }

    public function prePersist(LifecycleEventArgs $args)
    {

        $entity = $args->getObject();
        if (!$entity instanceof WebPage) {
            return;
        }

        if (empty($entity->getAlternativeHeadline())) {
            $entity->setAlternativeHeadline($entity->getHeadline());
        }

    }

    public function postUpdate(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();

        if (!$entity instanceof WebPage) {
            return;
        }

        if ('cli' !== php_sapi_name()) {
            $nuxtJsRouter = $this->container->get('app.nuxtjs.router');
            $nuxtJsRouter->generate($entity, ['route' => 'web_pages']);

            $nuxtJsExportJsonData = $this->container->get('app.nuxtjs.export_json_data');
            $nuxtJsExportJsonData->generate($entity, ['route' => 'web_pages']);
        }
    }

    public function postPersist(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();

        if (!$entity instanceof WebPage) {
            return;
        }

        if ('cli' !== php_sapi_name()) {
            $nuxtJsRouter = $this->container->get('app.nuxtjs.router');
            $nuxtJsRouter->generate($entity, ['route' => 'web_pages']);

            $nuxtJsExportJsonData = $this->container->get('app.nuxtjs.export_json_data');
            $nuxtJsExportJsonData->generate($entity, ['route' => 'web_pages']);
        }
    }

}
