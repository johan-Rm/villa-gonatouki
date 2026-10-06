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

use App\Entity\MediaObject;
use Cocur\Slugify\Slugify;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Liip\ImagineBundle\Imagine\Cache\CacheManager;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Filesystem\Filesystem;
use Vich\UploaderBundle\Event\Event;


/**
 * ...
 *
 * @author Johan REMY <johan.remy@graines-digitales.online>
 */
class MediaObjectListener
{
    /**
    * @var ContainerInterface
    */
    private $container;

    /**
    * @var CacheManager
    */
    private $cacheManager;

    /**
    * @var Filesystem
    */
    private $filesystem;

    /**
    * @var Tools\Media
    */
    private $toolsMediaService;

    /**
    * @var SEO\MetaData
    */
    private $seoMetaDataService;

    public function __construct(ContainerInterface $container, CacheManager $cacheManager, Filesystem $filesystem)
    {
        $this->container = $container;
        $this->cacheManager = $cacheManager;
        $this->filesystem = $filesystem;
        $this->toolsMediaService = $this->container->get('app.tools.media');
        $this->seoMetaDataService = $this->container->get('app.seo.meta_data');
    }

    public function preRemove(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        if (!$entity instanceof MediaObject) {
            return;
        }

        $this->toolsMediaService->remove($entity);
    }

    public function preUpdate(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();
        if (!$entity instanceof MediaObject) {
            return;
        }

        if ('cli' !== php_sapi_name() && true === $entity->getRegenerateFormat()) {
          $this->generatesMultipleFormats($entity);
        }
        $entity->setUrl($this->toolsMediaService->defineUrl($entity));
        $entity->setName($this->toolsMediaService->defineName($entity));
        $entity->setAlt($this->seoMetaDataService->defineAlt($entity));
    }

    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();
        if (!$entity instanceof MediaObject) {
            return;
        }

        if ('cli' !== php_sapi_name()) {
          $this->generatesMultipleFormats($entity);
        }
        $entity->setUrl($this->toolsMediaService->defineUrl($entity));
        $entity->setName($this->toolsMediaService->defineName($entity));
        $entity->setAlt($this->seoMetaDataService->defineAlt($entity));
    }

    private function generatesMultipleFormats($entity)
    {
      if (!in_array($entity->getEncodingFormat(), $this->toolsMediaService->getMimetypesRules())) {

        throw new \RuntimeException('Encoding format is not accepted');
      }

      $this->toolsMediaService->generatesMultipleFormats($entity);
    }

}
