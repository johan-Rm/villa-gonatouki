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

use Symfony\Component\Yaml\Yaml;
use Symfony\Component\Finder\Finder;
use Doctrine\Common\Util\Inflector;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\NullOutput;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use EasyCorp\Bundle\EasyAdminBundle\Event\EasyAdminEvents;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use EasyCorp\Bundle\EasyAdminBundle\Exception\ForbiddenActionException;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AdminController as BaseAdminController;


/**
 * ...
 *
 * @author Johan REMY <johan.remy@graines-digitales.online>
 */
abstract class AbstractController extends BaseAdminController
{

  /**
   * @param $entity
   */
  protected function setAdministrable($entity)
  {
      $params = $this->request->query->all();

      if (null == $entity->getId()) {
          if (is_callable([$entity, 'setUserCreated'])) {
              $entity->setUserCreated($this->getUser());
          }
      }

      if (is_callable([$entity, 'setUserLastModified'])) {
          $entity->setUserLastModified($this->getUser());
      }

      return $entity;
  }

    /**
     * @Route("/", name="easyadmin")
     *
     * @return RedirectResponse|Response
     *
     * @throws ForbiddenActionException
     */
    public function indexAction(Request $request)
    {
        $this->initialize($request);

        if (null === $request->query->get('entity')) {
            return $this->redirectToBackendHomepage();
        }

        $action = $request->query->get('action', 'list');
        if (!$this->isActionAllowed($action)) {
            throw new ForbiddenActionException(['action' => $action, 'entity_name' => $this->entity['name']]);
        }

        return $this->executeDynamicMethod($action.'<EntityName>Action');
    }

    /**
     * The method that is executed when the user performs a 'new' action on an entity.
     *
     * @return Response|RedirectResponse
     */
    protected function newAction()
    {
        $this->dispatch(EasyAdminEvents::PRE_NEW);

        $entity = $this->executeDynamicMethod('createNew<EntityName>Entity');

        $seoMetaData = $this->container->get('app.seo.meta_data');
        $easyadmin = $this->request->attributes->get('easyadmin');
        $easyadmin['item'] = $entity;
        $this->request->attributes->set('easyadmin', $easyadmin);

        $fields = $this->entity['new']['fields'];

        $newForm = $this->executeDynamicMethod('create<EntityName>NewForm', [$entity, $fields]);

        $newForm->handleRequest($this->request);
        if ($newForm->isSubmitted() && $newForm->isValid()) {

            /**
            * @todo à refactoriser
            */
            $entity = $this->setAdministrable($entity);
            $entity = $seoMetaData->process($entity);

            $this->dispatch(EasyAdminEvents::PRE_PERSIST, ['entity' => $entity]);
            $this->executeDynamicMethod('persist<EntityName>Entity', [$entity, $newForm]);
            $this->dispatch(EasyAdminEvents::POST_PERSIST, ['entity' => $entity]);

            // TRANSLATION
            $entityValues = $this->request->request->get(strtolower($easyadmin['entity']['name']));
            if (!empty($entityValues)) {
                if (null != $entity->getId()) {

                    // if (is_callable([$entity, 'getMetaTitle'])) {
                    //     $metaTitle = $entity->getMetaTitle();
                    //     $entityValues['metaTitle'] = $metaTitle;
                    // }
                    // if (is_callable([$entity, 'getMetaDescription'])) {
                    //     $metaDescription = $entity->getMetaDescription();
                    //     $entityValues['metaDescription'] = $metaDescription;
                    // }
                    // if (is_callable([$entity, 'getSlug'])) {
                    //     $slug = $entity->getSlug();
                    //     $entityValues['slug'] = $slug;
                    // }
                    // if (isset($entityValues['slug']) && null == $entityValues['slug']) {
                    //     $entityValues['slug'] = $entity->getSlug();
                    // }
                    //
                    // if (isset($easyadmin['entity']['translatable']) && $easyadmin['entity']['translatable']) {
                    //     $translator = $this->container->get('app.translation.easyadmin.translator');
                    //
                    //     $translator->setEntityIdentifier($entity->getId());
                    //     $translator->setEasyadminConfig($easyadmin['entity']);
                    //     $translator->process($entityValues);
                    //     $translator->write();// à modifier, pourquoi faire cette manip lors des mises à jour du front
                    // }

                }
            }

            return $this->redirectToReferrer();
        }

        $this->dispatch(EasyAdminEvents::POST_NEW, [
            'entity_fields' => $fields,
            'form' => $newForm,
            'entity' => $entity,
        ]);

        $parameters = [
            'form' => $newForm->createView(),
            'entity_fields' => $fields,
            'entity' => $entity,
        ];

        return $this->executeDynamicMethod('render<EntityName>Template', ['new', $this->entity['templates']['new'], $parameters]);
    }

    /**
     * The method that is executed when the user performs a 'edit' action on an entity.
     *
     * @return Response|RedirectResponse
     *
     * @throws \RuntimeException
     */
    protected function editAction()
    {
        $this->dispatch(EasyAdminEvents::PRE_EDIT);

        $seoMetaData = $this->container->get('app.seo.meta_data');

        $id = $this->request->query->get('id');
        $easyadmin = $this->request->attributes->get('easyadmin');
        $entity = $easyadmin['item'];

        

        if ($this->request->isXmlHttpRequest() && $property = $this->request->query->get('property')) {
            $newValue = 'true' === \mb_strtolower($this->request->query->get('newValue'));
            $fieldsMetadata = $this->entity['list']['fields'];

            if (!isset($fieldsMetadata[$property]) || 'toggle' !== $fieldsMetadata[$property]['dataType']) {
                throw new \RuntimeException(\sprintf('The type of the "%s" property is not "toggle".', $property));
            }

            $this->updateEntityProperty($entity, $property, $newValue);

            // cast to integer instead of string to avoid sending empty responses for 'false'
            return new Response((int) $newValue);
        }

        $fields = $this->entity['edit']['fields'];

        $editForm = $this->executeDynamicMethod('create<EntityName>EditForm', [$entity, $fields]);
        $deleteForm = $this->createDeleteForm($this->entity['name'], $id);

        // dump($this->request);die;
        $editForm->handleRequest($this->request);

        if (is_callable([$entity, 'setSlug'])) {
            $slug = $entity->setSlug(null);
        }
        
        if ($editForm->isSubmitted() && $editForm->isValid()) {
            
            /**
            * @todo à refactoriser
            */
            $entity = $this->setAdministrable($entity);
            $entity = $seoMetaData->process($entity);
            
            $this->dispatch(EasyAdminEvents::PRE_UPDATE, ['entity' => $entity]);
            $this->executeDynamicMethod('update<EntityName>Entity', [$entity, $editForm]);
            
            $this->dispatch(EasyAdminEvents::POST_UPDATE, ['entity' => $entity]);
            // die('end');
            // TRANSLATION
            $entityValues = $this->request->request->get(strtolower($easyadmin['entity']['name']));
            if (!empty($entityValues)) {
                if (null != $entity->getId()) {

                    // if (is_callable([$entity, 'getMetaTitle'])) {
                    //     $metaTitle = $entity->getMetaTitle();
                    //     $entityValues['metaTitle'] = $metaTitle;
                    // }
                    // if (is_callable([$entity, 'getMetaDescription'])) {
                    //     $metaDescription = $entity->getMetaDescription();
                    //     $entityValues['metaDescription'] = $metaDescription;
                    // }
                    // if (is_callable([$entity, 'getSlug'])) {
                    //     $slug = $entity->getSlug();
                    //     $entityValues['slug'] = $slug;
                    // }
                    // if (isset($entityValues['slug']) && null == $entityValues['slug']) {
                    //     $entityValues['slug'] = $entity->getSlug();
                    // }
                    //
                    // if (isset($easyadmin['entity']['translatable']) && $easyadmin['entity']['translatable']) {
                    //
                    //     $translator = $this->container->get('app.translation.easyadmin.translator');
                    //     $translator->setEntityIdentifier($entity->getId());
                    //     $translator->setEasyadminConfig($easyadmin['entity']);
                    //     $translator->process($entityValues);
                    //     $translator->write();// à modifier, pourquoi faire cette manip lors des mises à jour du front
                    // }

                }
            }

            if (isset($easyadmin['entity']['redirect']) && false === $easyadmin['entity']['redirect']) {
                return $this->redirectToRoute('easyadmin', [
                    'action' => 'edit',
                    'id' => $easyadmin['item']->getId(),
                    'entity' => $easyadmin['entity']['name'],
                ]);
            }

            return $this->redirectToReferrer();
        }

        $this->dispatch(EasyAdminEvents::POST_EDIT);

        $parameters = [
            'form' => $editForm->createView(),
            'entity_fields' => $fields,
            'entity' => $entity,
            'delete_form' => $deleteForm->createView(),
        ];

        return $this->executeDynamicMethod('render<EntityName>Template', ['edit', $this->entity['templates']['edit'], $parameters]);
    }

    //
    // /**
    //  * The method that is executed when the user performs a 'list' action on an entity.
    //  *
    //  * @return Response
    //  */
    // protected function listAction()
    // {
    //     $this->dispatch(EasyAdminEvents::PRE_LIST);
    //
    //     $fields = $this->entity['list']['fields'];
    //     $paginator = $this->findAll($this->entity['class'], $this->request->query->get('page', 1), $this->entity['list']['max_results'], $this->request->query->get('sortField'), $this->request->query->get('sortDirection'), $this->entity['list']['dql_filter']);
    //
    //     $this->dispatch(EasyAdminEvents::POST_LIST, ['paginator' => $paginator]);
    //
    //     $parameters = [
    //         'paginator' => $paginator,
    //         'fields' => $fields,
    //         'batch_form' => $this->createBatchForm($this->entity['name'])->createView(),
    //         'delete_form_template' => $this->createDeleteForm($this->entity['name'], '__id__')->createView(),
    //     ];
    //
    //     return $this->executeDynamicMethod('render<EntityName>Template', ['list', $this->entity['templates']['list'], $parameters]);
    // }

    public function buildingAction()
    {
        // https://ourcodeworld.com/articles/read/346/how-to-execute-a-symfony-command-from-a-controller
        // $kernel = $this->container->get('kernel');

        // $application = new Application($kernel);
        // $application->setAutoExit(false);

        // $input = new ArrayInput(array(
        //     'command' => 'app:generate-nuxtjs-routes'
        // ));

        // Use the NullOutput class instead of BufferedOutput.
        // $output = new NullOutput();

        // try {
        //     $application->run($input, $output);
        // } catch (ProcessFailedException $exception) {
        //     dump($exception->getMessage());
        //     die;
        // }

        // return $this->redirectToReferrer();

        // $output = new BufferedOutput();

        // try {
        //     $application->run($input, $output);
        // } catch (\Exception $exception) {
        //     dump($exception->getMessage());
        //     die;
        // }

        // // return the output
        // $content = $output->fetch();

        // $pdfTools = $this->container->get('app.tools.pdf_builder');
        // $pdfTools->generatePdfFormSourceFolder();


        // $translator = $this->container->get('app.translation.easyadmin.translator');
        // $finder = new Finder();
        
        // $finder->files()->in(
        //     $this->container
        //     ->get('kernel')
        //     ->getProjectDir().'/config/packages/easy_admin/entities/'
        // );

        // if ($finder->hasResults()) {
        //     $i = 0;
        //     $totalNewTranslate = [];
        //     foreach ($finder as $file) {
        //         if ('test_accommodation.yml' !== $file->getRelativePathname()) {
    
        //             $attributes = Yaml::parseFile($file->getRealPath());
        //             $entityName = $this->getEntityName($file->getRelativePathname());
        //             if (isset($attributes['easy_admin']['entities'][$entityName])) {
        //                 $attributes['easy_admin']['entities'][$entityName]['name'] = $entityName;
        //                 $attributes = $attributes['easy_admin']['entities'][$entityName];

        //                 if (isset($attributes['translatable']) && $attributes['translatable']
                           
        //                 ) {
                       

        //                     $repository = $this->em->getRepository($attributes['class']);
        //                     $results = $repository->createQueryBuilder('c')
        //                                             ->getQuery()
        //                                             ->getResult(\Doctrine\ORM\Query::HYDRATE_ARRAY);

                          
        //                     list($attributes['form']['fields'], $formFieldsForTable) = $this->getFormFields($attributes['form']['fields']);


        //                         foreach ($results as $key => $result) {

        //                             $translator->setEntityIdentifier($result['id']);
        //                             $translator->setEasyadminConfig($attributes);
        
        //                         }
        //                 }
        //             }
        //             $totalNewTranslate = array_merge($translator->newTranslate, $totalNewTranslate);
        //             $translator->newTranslate = [];
        //         }
        //     }
        // }
        // $translator->write();


        // die('maintenance on ne bouge plus!!');
        $exportJsonData = $this->container->get('app.nuxtjs.export_json_data');
        $exportJsonData->generateJsonData();

        $nuxtJsBuildService = $this->container->get('app.nuxtjs.build');
        $nuxtJsBuildService->execute();
// return true;
        return $this->redirectToReferrer();
        // $content = 'dump me';
        // $parameters['content'] = $content;
        // //
        // return $this->render('base.html.twig', $parameters);
    }

    private function getEntityName($relativePathname)
    {
        $entity = \pathinfo($relativePathname, PATHINFO_FILENAME);
        $entity = ucfirst(Inflector::camelize($entity));

        return $entity;
    }

    private function getFormFields($fields)
    {
        $formFields = [];
        $formFieldsForTable = [];
        foreach ($fields as $key => $value) {
            if (isset($value['property'])) {
                $formFields[$value['property']] = $value;
                $formFieldsForTable[] = [$value['property'], (isset($value['translatable']) ? 'true' : 'false')];
            }
        }

        return [$formFields, $formFieldsForTable];
    }
}
