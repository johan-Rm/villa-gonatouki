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

use App\Entity\Accommodation;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Knp\Snappy\Pdf;
use Liip\ImagineBundle\Imagine\Cache\CacheManager;
use Liip\ImagineBundle\Service\FilterService;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Process\Process;
use Symfony\Contracts\Translation\TranslatorInterface;
use Twig\Environment;

/**
 * ...
 *
 * @author Johan REMY <johan.remy@graines-digitales.online>
 */
class AccommodationListener
{
    private $knpSnappy;
    private $orm;
    private $assetsPdfPath;
    private $templating;
    private $cdnHost;
    private $container;
    private $translator;
    private $imagine;
    private $assetsMediaFolder;
    private $cacheManager;

    public function __construct(Pdf $knpSnappy, EntityManagerInterface $orm, ContainerInterface $container, Environment $templating, TranslatorInterface $translator, FilterService $imagine, CacheManager $cacheManager)
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

    public function preUpdate(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();

        if (!$entity instanceof Accommodation) {
            return;
        }

        if ('cli' !== php_sapi_name()) {
            $nuxtJsRouter = $this->container->get('app.nuxtjs.router');
            $nuxtJsRouter->generate(
                $entity, [
                    'route' => 'full_accommodations', 'nature' => $entity->getNature(),
                ]
            );
            $nuxtJsRouter->generate(
                $entity->getType(), [
                    'nature' => $entity->getNature(),
                ]
            );

            $nuxtJsExportJsonData = $this->container->get('app.nuxtjs.export_json_data');
            $nuxtJsExportJsonData->generate(
                $entity, [
                    'route' => 'full_accommodations', 'nature' => $entity->getNature(),
                ]
            );
            $nuxtJsExportJsonData->generate(
                $entity->getType(), [
                    'nature' => $entity->getNature(),
                ]
            );

            $nuxtJsExportPdfFile = $this->container->get('app.nuxtjs.export_pdf_file');
            $nuxtJsExportPdfFile->generate(
                $entity, [
                    'route' => 'full_accommodations', 'nature' => $entity->getNature(),
                ]
            );

            if (null == $entity->getReference()
                || true === $entity->getRegenerateReference()
            ) {
                $entity->setRegenerateReference(false);
                $reference = $this->createReference($entity);
                $entity->setReference($reference);
            }
        }
    }

    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();

        if (!$entity instanceof Accommodation) {
            return;
        }
    }

    public function postPersist(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();

        if (!$entity instanceof Accommodation) {
            return;
        }

        if ('cli' !== php_sapi_name()) {
            $nuxtJsRouter = $this->container->get('app.nuxtjs.router');
            $nuxtJsRouter->generate(
                $entity, [
                        'route' => 'full_accommodations', 'nature' => $entity->getNature(),
                    ]
                );
            $nuxtJsRouter->generate(
                $entity->getType(), [
                    'nature' => $entity->getNature(),
                ]
            );

            $nuxtJsExportJsonData = $this->container->get('app.nuxtjs.export_json_data');
            $nuxtJsExportJsonData->generate(
                $entity, [
                    'route' => 'full_accommodations', 'nature' => $entity->getNature(),
                ]
            );
            $nuxtJsExportJsonData->generate(
                $entity->getType(), [
                    'nature' => $entity->getNature(),
                ]
            );

            $nuxtJsExportPdfFile = $this->container->get('app.nuxtjs.export_pdf_file');
            $nuxtJsExportPdfFile->generate(
                $entity, [
                    'route' => 'full_accommodations', 'nature' => $entity->getNature(),
                ]
            );

            if (null == $entity->getReference()) {
                $reference = $this->createReference($entity);
                $entity->setReference($reference);
            }
        }
    }

    public function postRemove(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        if (!$entity instanceof Accommodation) {
            return;
        }

        if ('cli' !== php_sapi_name()) {
            $nuxtJsRouter = $this->container->get('app.nuxtjs.router');
            $nuxtJsRouter->generate(
                $entity, [
                        'route' => 'full_accommodations', 'nature' => $entity->getNature(),
                    ]
                );
            $nuxtJsRouter->generate(
                $entity->getType(), [
                    'nature' => $entity->getNature(),
                ]
            );

            $nuxtJsExportJsonData = $this->container->get('app.nuxtjs.export_json_data');
            $nuxtJsExportJsonData->generate(
                $entity, [
                    'route' => 'full_accommodations', 'nature' => $entity->getNature(),
                ]
            );
            $nuxtJsExportJsonData->generate(
                $entity->getType(), [
                    'nature' => $entity->getNature(),
                ]
            );
        }
    }

    public function createReference($entity)
    {
        $reference = null;
        $nature = $entity->getNature();
        $type = $entity->getType();
        if ('vente' == $nature->getSlug()) {
            $results = $this->orm->getRepository(Accommodation::class)
            ->findBySellingCriteria(['nature' => $nature, 'type' => $type]);
            $refs = [];
            foreach ($results as $key => $value) {
                if ($entity->getId() !== $value->getId()) {
                    $number = (int) filter_var($value->getReference(), FILTER_SANITIZE_NUMBER_INT);
                    $refs[$number] = $number;
                }
            }
            ksort($refs);
            $count = end($refs);
            $count = $count + 1;
            $initials = null;
            $initials .= strtoupper(substr($nature->getSlug(), 0, 1));
            $words = explode('-', $type->getSlug());
            foreach ($words as $word) {
                $initials .= strtoupper(substr($word, 0, 1));
            }
            $reference = $initials.$count;
        } elseif ('location' == $nature->getSlug()) {
            $duration = $entity->getDuration();
            $results = $this->orm->getRepository(Accommodation::class)
            ->findByRentingCriteria(['nature' => $nature, 'duration' => $duration]);
            $refs = [];
            foreach ($results as $key => $value) {
                if ($entity->getId() !== $value->getId()) {
                    $number = (int) filter_var($value->getReference(), FILTER_SANITIZE_NUMBER_INT);
                    $refs[$number] = $number;
                }
            }
            ksort($refs);
            $count = end($refs);
            $count = $count + 1;

            $initials = null;
            $initials .= strtoupper(substr($nature->getSlug(), 0, 1));
            $words = explode('-', $duration->getSlug());
            $initial = null;
            foreach ($words as $word) {
                $initial .= strtoupper(substr($word, 0, 1));
            }
            $initials .= $initial;
            $words = explode('-', $type->getSlug());
            $initial = null;
            foreach ($words as $word) {
                $initial .= strtoupper(substr($word, 0, 1));
            }
            $initials .= $initial;
            $reference = $initials.$count;
        }

        return $reference;
    }

    private function convertWebpToPng($sourceFilePath, $targetFilePath)
    {
        $cmd = [
            '/usr/bin/dwebp',
            $sourceFilePath,
            '-o',
            $targetFilePath,
        ];

        $process = new Process($cmd);
        $process->setTimeout(900);
        try {
            $process->mustRun();
        } catch (ProcessFailedException $exception) {
            throw new \RuntimeException($exception->getMessage());
        }
    }
}
