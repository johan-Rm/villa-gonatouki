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

use App\Entity\Article;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Liip\ImagineBundle\Imagine\Cache\CacheManager;
use Liip\ImagineBundle\Service\FilterService;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * ...
 *
 * @author Johan REMY <johan.remy@graines-digitales.online>
 */
class ArticleListener
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

        if (!$entity instanceof Article) {
            return;
        }

        if ('cli' !== php_sapi_name()) {
            $nuxtJsRouter = $this->container->get('app.nuxtjs.router');
            $nuxtJsRouter->generate($entity, ['route' => 'full_articles']);
            $nuxtJsRouter->generate($entity->getCategory(), ['route' => 'tags']);

            $nuxtJsExportJsonData = $this->container->get('app.nuxtjs.export_json_data');
            $nuxtJsExportJsonData->generate($entity, ['route' => 'full_articles']);
            $nuxtJsExportJsonData->generate($entity->getCategory(), ['route' => 'tags']);
        }
    }

    public function preUpdate(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();

        if (!$entity instanceof Article) {
            return;
        }

        if (empty($entity->getAlternativeHeadline())) {
            $entity->setAlternativeHeadline($entity->getHeadline());
        }

        if (empty($entity->getArticleResume())) {
            $resume = strip_tags($entity->getArticleBody());
            $resume = substr($resume, 0, 350);
            $resume = html_entity_decode($resume, ENT_QUOTES);
            $entity->setArticleResume(trim($resume));
        }


    }

    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();

        if (!$entity instanceof Article) {
            return;
        }

        if (empty($entity->getAlternativeHeadline())) {
            $entity->setAlternativeHeadline($entity->getHeadline());
        }

        if (empty($entity->getArticleResume())) {
            $resume = strip_tags($entity->getArticleBody());
            $resume = substr($resume, 0, 350);
            $resume = html_entity_decode($resume, ENT_QUOTES);
            $entity->setArticleResume(trim($resume));
        }


    }

    public function postUpdate(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();

        if (!$entity instanceof Article) {
            return;
        }

        if ('cli' !== php_sapi_name()) {
            $nuxtJsRouter = $this->container->get('app.nuxtjs.router');
            $nuxtJsRouter->generate($entity, ['route' => 'full_articles']);
            $nuxtJsRouter->generate($entity->getCategory(), ['route' => 'tags']);

            $nuxtJsExportJsonData = $this->container->get('app.nuxtjs.export_json_data');
            $nuxtJsExportJsonData->generate($entity, ['route' => 'full_articles']);
            $nuxtJsExportJsonData->generate($entity->getCategory(), ['route' => 'tags']);
        }
    }

    public function postPersist(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();
        if (!$entity instanceof Article) {
            return;
        }

        if ('cli' !== php_sapi_name()) {
            $nuxtJsRouter = $this->container->get('app.nuxtjs.router');
            $nuxtJsRouter->generate($entity, ['route' => 'full_articles']);

            $nuxtJsExportJsonData = $this->container->get('app.nuxtjs.export_json_data');
            $nuxtJsExportJsonData->generate($entity, ['route' => 'full_articles']);
            $nuxtJsExportJsonData->generate($entity->getCategory(), ['route' => 'tags']);
        }
    }


}
