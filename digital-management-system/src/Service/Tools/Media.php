<?php

/*
 * This file is part of the Graines Digitales DMS project.
 *
 * (c) Johan REMY <johan.remy@graines-digitales.online>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Service\Tools;

use Liip\ImagineBundle\Imagine\Cache\CacheManager;
use Liip\ImagineBundle\Service\FilterService;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Process\Process;
use Symfony\Component\Validator\Constraints\File;
use Cocur\Slugify\Slugify;
use App\Entity\MediaObject;


/**
 * ...
 *
 * @author Johan REMY <johan.remy@graines-digitales.online>
 */
class Media
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @var FilterService
     */
    private $imagine;

    /**
     * @var CacheManager
     */
    private $cacheManager;

    /**
     * @var Filesystem
     */
    private $filesystem;

    /**
    * @var array
    */
    private $parameters;

    public function __construct(
        FilterService $imagine, CacheManager $cacheManager, Filesystem $filesystem, ContainerInterface $container
    ) {
        $this->imagine = $imagine;
        $this->cacheManager = $cacheManager;
        $this->filesystem = $filesystem;
        $this->container = $container;
        $this->seoMetaDataService = $this->container->get('app.seo.meta_data');

        $this->parameters = $this->defineParameters();
    }


    public function generatesMultipleFormats($media)
    {
        $filter_sets = $this->container->getParameter(
            'liip_imagine.filter_sets'
        );
        $fileCache = $this->parameters['assetsUploadsMediaFolder'].DIRECTORY_SEPARATOR.$media->getFilename();
        foreach ($filter_sets as $key => $filter) {

            $cachePath = $this->parameters['assetsRootPath'].DIRECTORY_SEPARATOR;
            $cachePath .= $this->parameters['assetsCachePrefix'].DIRECTORY_SEPARATOR.$key.DIRECTORY_SEPARATOR;
            $fileCacheFullPath = $cachePath.DIRECTORY_SEPARATOR.$fileCache;
            if ($this->filesystem->exists($fileCacheFullPath)) {
                $this->cacheManager->remove($fileCache, $key);
            }
            $this->imagine->getUrlOfFilteredImage($fileCache, $key);
            // attention runtime creer des dossiers cryptés
            // $this->imagine->getUrlOfFilteredImageWithRuntimeFilters($sourceFileFolder, $key, $runtimeConfig);

            $mediaName = pathinfo($media->getFilename(), PATHINFO_FILENAME);
            $webpFileCache = $this->parameters['assetsUploadsMediaFolder'];
            $webpFileCache.= DIRECTORY_SEPARATOR.$mediaName.'.webp';
            $webpFileCacheFullPath = $cachePath.DIRECTORY_SEPARATOR.$webpFileCache;
            if ($this->filesystem->exists($webpFileCache)) {
                $this->cacheManager->remove($webpFileCacheFullPath, $key);
            }
            $this->compressFileInWebpFormat($fileCacheFullPath, $webpFileCacheFullPath);
        }
    }

    public function defineEntityMedia($file)
    {
        $slugify = new Slugify();
        $cdnHost = $this->container->getParameter('cdn.host');
        $absoluteFilePath = $file->getRealPath();
        $filePath = $file->getPath();
        $currentFolder = basename($file->getPath());
        $relativePathname = $file->getRelativePathname();
        $originalFilename = basename($relativePathname);
        $ext = pathinfo($relativePathname, PATHINFO_EXTENSION);
        $encodingFormat = 'image/'.$file->getExtension();
        $dimensions = getimagesize($file->getPathname());
        $contentSize = $file->getSize();
        $format = '';
        if ($dimensions[1] > $dimensions[0]) {
            $format = '-vertical';
        }
        $filename = pathinfo($originalFilename, PATHINFO_FILENAME);
        $name = $slugify->slugify($currentFolder.' '.$filename);
        $filename = $name.$format.'.'.$ext;
        $name = ucwords(str_replace('-', ' ', $name));

        $media = new MediaObject();
        $media->setName($name);
        $media->setUrl($cdnHost.DIRECTORY_SEPARATOR.$this->parameters['assetsUploadsMediaFolder'].DIRECTORY_SEPARATOR.$filename);
        $media->setDimensions($dimensions);
        $media->setOriginalFilename($originalFilename);
        $media->setFilename($filename);
        $media->setEncodingFormat($encodingFormat);
        $media->setContentSize($contentSize);
        $media->setIsConform(false);
        $media->setRegenerateFormat(false);
        $alt = $this->seoMetaDataService->defineAlt($media);
        $media->setAlt($alt);

        return $media;
    }

    public function getMediaArray($manager)
    {
        $results = $manager->getRepository(MediaObject::class)->findAll();
        $medias = [];
        foreach ($results as $key => $value) {
            $medias[$value->getFilename()] = $value;
        }

        return $medias;
    }

    public function formatBytes($bytes, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB'];

        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1000));
        $pow = min($pow, count($units) - 1);

        $bytes /= pow(1000, $pow);

        return round($bytes, $precision).' '.$units[$pow];
    }

    public function getMimetypesRules()
    {
        $annotationEntityService = $this->container->get(
            'app.export.annotation_entity'
        );
        $constraints = $annotationEntityService->getContraintsByField(
            MediaObject::class, 'file'
        );

        $mimeTypes = [];
        foreach ($constraints as $constraint) {
            if ($constraint instanceof File) {
                $mimeTypes = $constraint->mimeTypes;
            }
        }

        return $mimeTypes;
    }



    public function defineName($media)
    {
        if (empty($media->getName())) {
            $slugify = new Slugify();
            $name = pathinfo($media->getFilename(), PATHINFO_FILENAME);
            $name = $slugify->slugify($name);

            return $name;
        }

        return $media->getName();
    }

    public function defineUrl($media)
    {
        $cdnHost = $this->container->getParameter('cdn.host');
        $assetsMediaFolder = $this->container->getParameter(
              'assets.uploads.media.folder'
        );

        $url = $media->getUrl();
        if ('video/youtube' !== $media->getEncodingFormat()) {
            return $cdnHost.$assetsMediaFolder.'/'.$media->getFilename();
        }

        return $url;
    }

    public function remove($media)
    {
      $webpFilename = pathinfo($media->getFilename(), PATHINFO_FILENAME).'.'.'webp';
      $webpFileCachePath = $this->parameters['assetsUploadsMediaFolder'].'/'.$webpFilename;

      $fileCachePath = $this->parameters['assetsUploadsMediaFolder'].'/'.$media->getFilename();
      $fileUploadsMediaPath = $this->parameters['assetsUploadsMediaFolderPath'].'/'.$media->getFilename();

      $this->cacheManager->remove($fileCachePath);
      $this->cacheManager->remove($webpFileCachePath);
      $this->filesystem->remove($fileUploadsMediaPath);
    }

    public function checkIfFileAlreadyUploaded($filename)
    {
      $targetFilePath = $this->parameters['assetsRootPath'];
      $targetFilePath.= DIRECTORY_SEPARATOR . $this->parameters['assetsUploadsMediaFolder'];
      $targetFilePath.= DIRECTORY_SEPARATOR . $filename;
      if($this->filesystem->exists($targetFilePath)) {

          return true;
      }

      return false;
    }

    public function copyFileToUploadsFolder($filename, $sourceFilePath)
    {
      $targetFilePath = $this->parameters['assetsRootPath'];
      $targetFilePath.= DIRECTORY_SEPARATOR . $this->parameters['assetsUploadsMediaFolder'];
      $targetFilePath.= DIRECTORY_SEPARATOR . $filename;
      if(!$this->filesystem->exists($targetFilePath)) {
          $this->filesystem->copy($sourceFilePath, $targetFilePath);
      }
    }

    private function defineParameters()
    {
      $assetsRootPath = $this->container->getParameter('assets.path');
      if (!$this->filesystem->exists($assetsRootPath)) {
          $this->filesystem->mkdir($assetsRootPath);
      }
      $assetsUploadsMediaFolder = $this->container->getParameter('assets.uploads.media.folder');
      $assetsUploadsMediaFolderPath = $assetsRootPath.DIRECTORY_SEPARATOR;
      $assetsUploadsMediaFolderPath .= $assetsUploadsMediaFolder.DIRECTORY_SEPARATOR;
      $assetsCachePrefix = $this->container->getParameter('assets.cache_prefix');

      return [
          'assetsRootPath' => $assetsRootPath,
          'assetsUploadsMediaFolder' => $assetsUploadsMediaFolder,
          'assetsUploadsMediaFolderPath' => $assetsUploadsMediaFolderPath,
          'assetsCachePrefix' => $assetsCachePrefix
      ];
    }

    private function compressFileInWebpFormat($sourceFilePath, $targetFilePath, $quality = 70)
    {
        $cmd = [
            '/usr/bin/cwebp',
            '-q',
            $quality,
            $sourceFilePath,
            '-o',
            $targetFilePath,
        ];
        $process = new Process($cmd);
        $process->setTimeout(900);
        $process->mustRun();
    }
}
