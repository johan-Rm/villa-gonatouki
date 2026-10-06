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
use Knp\Snappy\Pdf;
use Liip\ImagineBundle\Imagine\Cache\CacheManager;
use Liip\ImagineBundle\Service\FilterService;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use Twig\Environment;

/**
 * ...
 *
 * @author Johan REMY <johan.remy@graines-digitales.online>
 */
class AbstractListener
{
    /**
    * @var Pdf
    */
    private $knpSnappy;

    /**
    * @var EntityManagerInterface
    */
    private $orm;

    /**
    * @var Environment
    */
    private $templating;

    /**
    * @var ContainerInterface
    */
    private $container;

    /**
    * @var TranslatorInterface
    */
    private $translator;

    /**
    * @var FilterService
    */
    private $imagine;

    /**
    * @var CacheManager
    */
    private $cacheManager;

    /**
    * @var string
    */
    private $assetsPdfPath;

    /**
    * @var string
    */
    private $assetsMediaFolder;

    /**
    * @var string
    */
    private $cdnHost;

    public function __construct(
      Pdf $knpSnappy,
      EntityManagerInterface $orm,
      ContainerInterface $container,
      Environment $templating,
      TranslatorInterface $translator,
      FilterService $imagine,
      CacheManager $cacheManager
    )
    {
        $this->knpSnappy = $knpSnappy;
        $this->orm = $orm;
        $this->templating = $templating;
        $this->container = $container;
        $this->translator = $translator;
        $this->imagine = $imagine;
        $this->cacheManager = $cacheManager;

        $this->assetsPdfPath = $this->container->getParameter('assets.pdf.path');
        $this->assetsMediaFolder = $this->container->getParameter('assets.uploads.media.folder');
        $this->cdnHost = $this->container->getParameter('cdn.host');
    }
}
