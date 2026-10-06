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

use App\Entity\Accommodation;
use App\Entity\AccommodationType;
use App\Entity\Article;
use App\Entity\WebPage;
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
class Router
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
            $this->container->getParameter('view.update.build_routes.path')
        )) {
            $filesystem->mkdir(
                $this->container->getParameter('view.update.build_routes.path')
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
            'baseUrl' => $this->getBaseUrl($entity, $options),
        ];

        $nuxtRoutesToBuildFolder = $this->container->getParameter('view.update.build_routes.path').DIRECTORY_SEPARATOR;
        $json = json_encode($content, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);

        try {
            $filesystem->dumpFile(
                $nuxtRoutesToBuildFolder.$filename.'.json', $json
            );
        } catch (IOExceptionInterface $exception) {
            dump($exception);
            dump('Generate Routes : An error occurred while generating your routes at '.$exception->getPath());
            exit;
        }
    }

    private function getBaseUrl($entity, $options)
    {
        // dump($options);die;
        // if article => /actualite/slug-article
        if ($entity instanceof Article) {
            return '/actualite/'.$entity->getCategory()->getSlug().'/';
        }
        // if webPage => /slug-webPage
        if ($entity instanceof WebPage) {
            return '/';
        }
        // id accommodationType => /nature/slug-accommodation-type
        if ($entity instanceof AccommodationType) {
            return '/'.$options['nature']->getSlug().'/';
        }
        // if accommodation => /nature/type/slug-accommodation
        if ($entity instanceof Accommodation) {
            return '/'.$options['nature']->getSlug()
                        .'/'.$entity->getType()->getSlug().'/';
        }
    }
}
