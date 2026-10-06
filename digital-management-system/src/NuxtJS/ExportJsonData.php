<?php

namespace App\NuxtJS;

use Doctrine\ORM\EntityManager;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use Symfony\Component\Finder\Finder;
use Doctrine\Common\Util\Inflector;


class ExportJsonData
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
     * ou du moins il faudrait stocker les regles des routes quelques part
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
            $this->container->getParameter('view.update.json_data.path')
        )) {
            $filesystem->mkdir(
                $this->container->getParameter('view.update.json_data.path')
            );
        }

        $slug = $entity->getSlug();
        $entityName = Inflector::camelize((new \ReflectionClass($entity))->getShortName());
        $route  = Inflector::tableize(Inflector::pluralize($entityName));
        if (isset($options['route'])) {
            $route = $options['route'];
        }
        $path   = '/' . $route . '?slug=' . $slug;
        $filename = $entityName . '-' . $slug;
        $pathFile = $this->container->getParameter('view.project_dir');


        $content = array(
            'pathFile' => $pathFile,
            'slug' => $slug,
            'entity' => $entityName,
            'route' => $route,
            'path' => $path,
            'filename' => $filename,
            // 'baseUrl' => $this->getBaseUrl($entity, $options)
        );


        $nuxtRoutesToBuildFolder = $this->container->getParameter('view.update.json_data.path') . DIRECTORY_SEPARATOR;
        $json = json_encode($content, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES |  JSON_UNESCAPED_UNICODE);


        try {
            $filesystem->dumpFile(
                $nuxtRoutesToBuildFolder . $filename . '.json',
                $json
            );
        } catch (IOExceptionInterface $exception) {
            dump($exception);
            dump("Generate Json Data Files : An error occurred while generating your json data files at " . $exception->getPath());
            die;
        }
    }

    public function generateJsonData()
    {
        $exportApiToJsonService = $this->container->get('app.export.api_to_json');
        $viewHost = $this->container->getParameter('view.host');
        $path = $this->container->getParameter(
            'view.update.json_data.path'
        );
        $filesystem = new Filesystem();
        if (!$filesystem->exists(
            $path . DIRECTORY_SEPARATOR . 'tmp'
        )) {
            $filesystem->mkdir(
                $path . DIRECTORY_SEPARATOR . 'tmp'
            );
        } else {
            $finder = new Finder();
            $finder->depth('== 0');
            $filesystem = new Filesystem();
            $finder->files()->in($path . DIRECTORY_SEPARATOR . 'tmp');
            if ($finder->hasResults()) {
                foreach ($finder as $file) {
                    $absoluteFilePath = $file->getRealPath();
                    $filesystem->remove($absoluteFilePath);
                }
            }
        }
        $finder = new Finder();
        $finder->depth('== 0');
        $filesystem = new Filesystem();
        $finder->files()->in($path);
        if ($finder->hasResults()) {

            foreach ($finder as $file) {
                $absoluteFilePath = $file->getRealPath();
                $filePath = $file->getPath();
                $fileNameWithExtension = $file->getRelativePathname();
                $ext = pathinfo($fileNameWithExtension, PATHINFO_EXTENSION);
                $file = basename($fileNameWithExtension);
                $filename = basename($fileNameWithExtension, "." . $ext);

                $targetPath = $path . DIRECTORY_SEPARATOR . 'tmp' . DIRECTORY_SEPARATOR . $filename;
                if ($filesystem->exists($targetPath)) {
                    $filesystem->remove($targetPath);
                }
                $filesystem->rename($absoluteFilePath, $targetPath);
                $filesystem->remove($absoluteFilePath);
            }
        }


        $finder = new Finder();
        $finder->depth('== 0');
        $filesystem = new Filesystem();
        $finder->files()->in($path . DIRECTORY_SEPARATOR . 'tmp');
        if ($finder->hasResults()) {
            // si une les données ont déja été récupérés on ne refait pas le call api
            $apiCalls = [];
            $logs = [];
            $routesLog = [];
            foreach ($finder as $file) {
                $absoluteFilePath = $file->getRealPath();
                $fileNameWithExtension = $file->getRelativePathname();
                $routeConfig = json_decode($file->getContents(), true);
                if (!isset($apiCalls[$routeConfig['route']])) {
                    
                    $exportApiToJsonService->generateList($routeConfig['route']);
                    $apiCalls[$routeConfig['route']] = true;
                    $filesystem->remove($absoluteFilePath);
                }
            }
        } else {

            return false;
        }

        $exportApiToJsonService->generateSerializable('full');

        return true;
    }
}
