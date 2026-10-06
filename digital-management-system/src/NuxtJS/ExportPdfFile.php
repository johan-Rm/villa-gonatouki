<?php

/*
 * This file is part of the Graines Digitales DMS project.
 *
 * (c) Johan REMY <johan.remy@graines-digitales.online>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\NuxtJS;

use Doctrine\Common\Util\Inflector;
use Doctrine\ORM\EntityManager;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use Symfony\Component\Filesystem\Filesystem;


/**
 * ...
 *
 * @author Johan REMY <johan.remy@graines-digitales.online>
 */
class ExportPdfFile
{
    private $container;

    private $em;

    public function __construct(ContainerInterface $container, EntityManager $em)
    {
        $this->container = $container;
        $this->em = $em;
    }

    /**
     * Le router symfony doit se baser sur la configuration nuxtjs ?
     * ou du moins il faudrait stocker les regles des routes quelques part.
     **/
    public function generate($entity, $options = [])
    {
        /**
            "pathFile": "/home/www/graines-digitales/immobiliere-essaouira/cms/../view",
            "slug": "villa-golf",
            "entity": "accommodationType",
            "route": "accommodation_types",
            "path": "/accommodation_types?slug=villa-golf",
            "filename": "accommodationType-villa-golf",
            "baseUrl": "/vente/"
         **/
        $filesystem = new Filesystem();
        if (!$filesystem->exists(
            $this->container->getParameter('view.update.pdf_files.path')
        )) {
            $filesystem->mkdir(
                $this->container->getParameter('view.update.pdf_files.path')
            );
        }

        $slug = $entity->getSlug();
        $entityName = Inflector::camelize((new \ReflectionClass($entity))->getShortName());
        $route = Inflector::tableize(Inflector::pluralize($entityName));
        if (isset($options['route'])) {
            $route = $options['route'];
        }
        $path = '/'.$route.'?slug='.$slug;
        $filename = $entityName.'-'.$slug;
        $pathFile = $this->container->getParameter('view.project_dir');

        $content = [
            'pathFile' => $pathFile,
            'slug' => $slug,
            'entity' => $entityName,
            'route' => $route,
            'path' => $path,
            'filename' => $filename,
            // 'baseUrl' => $this->getBaseUrl($entity, $options)
        ];

        $nuxtRoutesToBuildFolder = $this->container->getParameter('view.update.pdf_files.path').DIRECTORY_SEPARATOR;
        $json = json_encode($content, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);

        try {
            $filesystem->dumpFile(
                $nuxtRoutesToBuildFolder.$filename.'.json', $json
            );
        } catch (IOExceptionInterface $exception) {
            dump($exception);
            dump('Generate Json Pdf Files : An error occurred while generating your json pdf files at '.$exception->getPath());
            exit;
        }
    }
}
