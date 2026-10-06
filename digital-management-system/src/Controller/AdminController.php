<?php

/*
 * This file is part of the Graines Digitales DMS project.
 *
 * (c) Johan REMY <johan.remy@graines-digitales.online>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Controller;

use App\Entity\Accommodation;
use App\Entity\MediaObject;
use Cocur\Slugify\Slugify;
use EasyCorp\Bundle\EasyAdminBundle\Event\EasyAdminEvents;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AdminController as BaseAdminController;
use EasyCorp\Bundle\EasyAdminBundle\Exception\ForbiddenActionException;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\Export\Csv;


/**
 * ...
 *
 * @author Johan REMY <johan.remy@graines-digitales.online>
 */
// class AdminController extends BaseAdminController
class AdminController extends AbstractController
{
    /**
    * @var Csv
    */
    private $csv;

    public function __construct(Csv $csv)
    {
        $this->csv = $csv;
    }

    /**
     * @Route("/phpinfo", name="easyadmin_phpinfo")
     */
    public function phpInfoAction(): Response
    {
        if ($this->container->has('profiler')) {
            $this->container->get('profiler')->disable();
        }
        ob_start();
        phpinfo();
        $str = ob_get_contents();
        ob_get_clean();

        return new Response($str);
    }

    /**
     * The method that is executed when the user performs a 'list' action on an entity.
     *
     * @return Response
     */
    protected function listMediaAction()
    {
        $this->dispatch(EasyAdminEvents::PRE_LIST);

        $fields = $this->entity['list']['fields'];
        $paginator = $this->findAll($this->entity['class'], $this->request->query->get('page', 1), $this->entity['list']['max_results'], $this->request->query->get('sortField'), $this->request->query->get('sortDirection'), $this->entity['list']['dql_filter']);

        $this->dispatch(EasyAdminEvents::POST_LIST, ['paginator' => $paginator]);

        $parameters = [
            'paginator' => $paginator,
            'fields' => $fields,
            'delete_form_template' => $this->createDeleteForm($this->entity['name'], '__id__')->createView(),
        ];

        return $this->executeDynamicMethod('render<EntityName>Template', ['list', 'pages/list.html.twig', $parameters]);
    }

    /**
     * @Route("/dashboard", name="dashboard")
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function dashboardAction(Request $request)
    {
        $parameters['headline'] = 'Dashboard Coming Soon';

        return $this->render('pages/dashboard.html.twig', $parameters);
    }

    /**
     * @Route("/coming_soon", name="coming_soon")
     *
     * @return RedirectResponse|Response
     *
     * @throws ForbiddenActionException
     */
    public function comingSoonAction(Request $request)
    {
        $parameters['headline'] = 'Coming soon';
        $parameters['about'] = 'The false text is, in print, a text without meaning, whose sole purpose is to calibrate the content ...';

        return $this->render('pages/coming_soon.html.twig', $parameters);
    }

    public function downloadAction()
    {
        throw new \RuntimeException('Action for download an entity not defined');
    }

    /**
     * @ Method({"GET", "POST"})
     * @Route("/ajax/media/download", name="ajax_media_download")
     */
    public function ajaxMediaDownloadAction(Request $request)
    {
        $em = $this->container->get('doctrine.orm.default_entity_manager');
        /** @var UploadedFile $uploadedFile */
        $uploadedFile = $request->files->get('file');

        $filename = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);
        $assetsMediaPath = $this->container->getParameter('assets.path').DIRECTORY_SEPARATOR.$this->container->getParameter('assets.uploads.media.folder');

        $filesystem = new Filesystem();
        
        if (!$filesystem->exists($assetsMediaPath.DIRECTORY_SEPARATOR.$filename.'.jpg')) {
            if (null !== $uploadedFile) {
                var_dump($uploadedFile);
                var_dump($uploadedFile->getMimeType());
                var_dump($uploadedFile->getClientOriginalName());
                var_dump($uploadedFile->getClientOriginalExtension());
                var_dump($uploadedFile->getPath());
                die;
                $media = new MediaObject();
                $media->setName($uploadedFile->getClientOriginalName());
                $media->setUrl($uploadedFile->getPath());
                $media->setDimensions([1920, 1281]);
                $media->setOriginalFilename($uploadedFile->getClientOriginalName());
                $media->setFilename($uploadedFile->getClientOriginalName());
                $media->setEncodingFormat($uploadedFile->getMimeType());
                $media->setContentSize($uploadedFile->getSize());
                $media->setFile($uploadedFile);
                $em->persist($media);
                $em->flush();

                return new JsonResponse(['success' => true]);
            }
        }

        return new JsonResponse(['success' => false]);
    }

    /**
     * The method that is executed when the user performs a 'list' action on an entity.
     *
     * @return Response
     */
    protected function listBuildRouteAction()
    {
        $nuxtProjectDir = $this->container->getParameter('view.project_dir');
        // dump($nuxtProjectDir);
        $host = $this->container->getParameter('view.host');
        // $filesystem = new Filesystem();
        $finder = new Finder();
        $path = $this->container->getParameter('view.update.build_routes.path');
        $results = [];
        $i = 0;
        $finder->files()->in($path);
        if ($finder->hasResults()) {
            foreach ($finder as $file) {
                $absoluteFilePath = $file->getRealPath();
                $filePath = $file->getPath();
                $fileNameWithExtension = $file->getRelativePathname();
                $ext = pathinfo($fileNameWithExtension, PATHINFO_EXTENSION);
                $file = basename($fileNameWithExtension);
                $filename = basename($fileNameWithExtension, '.'.$ext);
                $jsonFile = json_decode(file_get_contents($absoluteFilePath), true);
                // $slug = $slugify->slugify($file);
                // dump($absoluteFilePath);
                // dump($file);
                // dump($jsonFile);
                $results[$i]['id'] = $i;
                $results[$i]['name'] = $host.$jsonFile['baseUrl'].$jsonFile['slug'];
                ++$i;
                // die;
            }
        }

        $this->dispatch(EasyAdminEvents::PRE_LIST);

        $fields = $this->entity['list']['fields'];
        $paginator = $this->findAll($this->entity['class'], $this->request->query->get('page', 1), $this->entity['list']['max_results'], $this->request->query->get('sortField'), $this->request->query->get('sortDirection'), $this->entity['list']['dql_filter']);

        // dump($paginator);
        // die;
        // $paginator['currentPageResults'] = $results;
        $this->dispatch(EasyAdminEvents::POST_LIST, ['paginator' => $paginator]);

        $parameters = [
            'currentPageResults' => $results,
            'headline' => 'Building list pages',
            'paginator' => $paginator,
            'fields' => $fields,
            '_has_batch_actions' => false,
            'delete_form_template' => $this->createDeleteForm(
                $this->entity['name'], '__id__'
            )->createView(),
        ];

        return $this->executeDynamicMethod('render<EntityName>Template', ['list', 'pages/building_list.html.twig', $parameters]);
    }

    /**
     * The method that is executed when the user performs a 'list' action on an entity.
     *
     * @return Response
     */
    protected function listPdfCheckAction()
    {
        $nuxtProjectDir = $this->container->getParameter('view.project_dir');
        $host = $this->container->getParameter('view.host');
        // dump($nuxtProjectDir);
        $repository = $this->em->getRepository(Accommodation::class);
        $mediaService = $this->container->get('app.tools.media');
        $slugify = new Slugify();
        // $filesystem = new Filesystem();
        $finder = new Finder();
        $path = $this->container->getParameter('assets.pdf.path');
        $results = [];
        $i = 0;
        $finder->files()->in($path);
        if ($finder->hasResults()) {
            foreach ($finder as $file) {
                $absoluteFilePath = $file->getRealPath();
                $filePath = $file->getPath();
                $fileNameWithExtension = $file->getRelativePathname();
                $ext = pathinfo($fileNameWithExtension, PATHINFO_EXTENSION);
                $file = pathinfo($fileNameWithExtension, PATHINFO_FILENAME);
                $filename = basename($fileNameWithExtension, '.'.$ext);
                // $jsonFile = json_decode(file_get_contents($absoluteFilePath), true);
                $slug = $slugify->slugify($file);

                $result = $repository->findOneBy(['slug' => $slug, 'isActive' => true]);

                if ($result) {
                    $bytes = round(filesize($absoluteFilePath) / 1024);

                    // dump($file);
                    // dump($jsonFile);
                    if ($bytes < 100) {
                        $results[$i]['id'] = $i;
                        $results[$i]['name'] = $filename;
                        $bytes = $mediaService->formatBytes($bytes);
                        $results[$i]['taille'] = $bytes;
                        $results[$i]['reference'] = $result->getReference();
                        ++$i;
                    }
                }
                // die;
            }
        }

        $this->dispatch(EasyAdminEvents::PRE_LIST);

        $fields = $this->entity['list']['fields'];
        $paginator = $this->findAll($this->entity['class'], $this->request->query->get('page', 1), $this->entity['list']['max_results'], $this->request->query->get('sortField'), $this->request->query->get('sortDirection'), $this->entity['list']['dql_filter']);

        // dump($paginator);
        // die;
        // $paginator['currentPageResults'] = $results;
        $this->dispatch(EasyAdminEvents::POST_LIST, ['paginator' => $paginator]);

        $parameters = [
            'currentPageResults' => $results,
            'headline' => 'Pdf Size Check List',
            'paginator' => $paginator,
            'fields' => $fields,
            '_has_batch_actions' => false,
            'delete_form_template' => $this->createDeleteForm(
                $this->entity['name'], '__id__'
            )->createView(),
        ];

        return $this->executeDynamicMethod('render<EntityName>Template', ['list', 'pages/pdf_check.html.twig', $parameters]);
    }

    public function exportAction()
    {
        $sortDirection = $this->request->query->get('sortDirection');
        if (empty($sortDirection) || !in_array(strtoupper($sortDirection), ['ASC', 'DESC'])) {
            $sortDirection = 'DESC';
        }

        // $this->entity['list']['dql_filter'] = 'entity.nature = 1';

        $queryBuilder = $this->createListQueryBuilder(
            $this->entity['class'],
            $sortDirection,
            $this->request->query->get('sortField'),
            $this->entity['list']['dql_filter']
        );

        $repository = $this->em->getRepository($this->entity['class']);
        // dump($repository);die;
        $columns = $repository->getColumnsForCsv($this->entity['class']);
        // dump($this->entity);
        // dump($columns);
        // dump($this->request->query->get('params'));
        // die;

        return $this->csv->getResponseFromQueryBuilder(
           $queryBuilder,
           $columns,
           $this->request->query->get('params'),
           'export_test2.csv'
        );
    }

    public function reorderAction()
    {
        // dump($this->request->query->get('ext_filters'));
        // die('ici');
        if (isset($this->request->query->get('ext_filters')['entity.gallery'])) {
            $id = $this->request->query->get('ext_filters')['entity.gallery'];
            $repository = $this->em->getRepository(Gallery::class);
            $result = $repository->findOneById($id);

            $i = 0;
            foreach ($result->getImageGalleries() as $media) {
                // dump($media->getImage()->getName());
                // dump($media->getPosition());

                ++$i;
                $media->setPosition($i);
                $this->em->persist($media);
                $this->em->flush();
            }
        }

        return $this->redirectToReferrer();
    }

    public function translateAction()
    {
        exit('dieee');
        $translator = $this->container->get('app.translation.easyadmin.translator');
        $i = 0;
        foreach ($this->config['entities'] as $attributes) {
            if (isset($attributes['translatable']) && $attributes['translatable']) {
                $repository = $this->em->getRepository($attributes['class']);
                $results = $repository->createQueryBuilder('c')
                    ->getQuery()
                    ->getResult(\Doctrine\ORM\Query::HYDRATE_ARRAY);
                foreach ($results as $result) {
                    $translator->process(
                        $result, $attributes, $result['id']
                    );
                    if ($i > 0 && 0 == $i % 10) {
                        sleep(1);
                    }
                    ++$i;
                }
            }
        }
        $translator->write();

        return $this->redirectToReferrer();
    }

    public function dataAction()
    {
        $pdfTools = $this->container->get('app.tools.pdf_builder');
        $pdfTools->generatePdfFormSourceFolder();

        $exportJsonData = $this->container->get('app.nuxtjs.export_json_data');
        $exportJsonData->generateJsonData();

        return $this->redirectToReferrer();
    }

 
    protected function defineTheTranslationChain($values, $attributes, $id = null)
    {
        if (isset($attributes['translatable']) && $attributes['translatable']) {
            $translator = $this->container->get('app.translation.easyadmin.translator');
            $translator->process(
                $values, $attributes, $id
            );
            $translator->write();
        }
    }


}
