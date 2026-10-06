<?php

namespace App\Controller;

use EasyCorp\Bundle\EasyAdminBundle\Event\EasyAdminEvents;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use App\Entity\Gallery;
use App\Entity\ImageGallery;
use Symfony\Component\HttpFoundation\JsonResponse;


class GalleryController extends AbstractController
{
    /**
     * The method that is executed when the user performs a 'list' action on an entity.
     *
     * @return Response
     */
    protected function listGalleryAction()
    {
        // $this->entity['list']['dql_filter'] = "entity.component =  1";
        // $this->dispatch(EasyAdminEvents::PRE_LIST);

        // $fields = $this->entity['list']['fields'];
        // $paginator = $this->findAll($this->entity['class'], $this->request->query->get('page', 1), $this->entity['list']['max_results'], $this->request->query->get('sortField'), $this->request->query->get('sortDirection'), $this->entity['list']['dql_filter']);

        // $this->dispatch(EasyAdminEvents::POST_LIST, ['paginator' => $paginator]);

        // $parameters = [
        //     'paginator' => $paginator,
        //     'fields' => $fields,
        //     'delete_form_template' => $this->createDeleteForm($this->entity['name'], '__id__')->createView(),
        // ];

        // return $this->executeDynamicMethod('render<EntityName>Template', ['list', $this->entity['templates']['list'], $parameters]);


        //         $this->dispatch(EasyAdminEvents::PRE_LIST);

        // $fields = $this->entity['list']['fields'];
        // $paginator = $this->findAll($this->entity['class'], $this->request->query->get('page', 1), $this->entity['list']['max_results'], $this->request->query->get('sortField'), $this->request->query->get('sortDirection'), $this->entity['list']['dql_filter']);

        // $this->dispatch(EasyAdminEvents::POST_LIST, ['paginator' => $paginator]);

        // $parameters = [
        //     'paginator' => $paginator,
        //     'fields' => $fields,
        //     'batch_form' => $this->createBatchForm($this->entity['name'])->createView(),
        //     'delete_form_template' => $this->createDeleteForm($this->entity['name'], '__id__')->createView(),
        // ];

        // return $this->executeDynamicMethod('render<EntityName>Template', ['list', $this->entity['templates']['list'], $parameters]);

        // dump($this->entity);die;

        return parent::listAction();

    }
 
    /**
     * The method that is executed when the user performs a 'new' action on an entity.
     *
     * @return Response|RedirectResponse
     *
     * @throws \RuntimeException
     */
    protected function newGalleryAction()
    {
        // dump('yo controller');
        // dump($this->request);
        // die();

        return parent::newAction();
    }

    /**
     * The method that is executed when the user performs a 'edit' action on an entity.
     *
     * @return Response|RedirectResponse
     *
     * @throws \RuntimeException
     */
    protected function editGalleryAction()
    {   
        
        $id = $this->request->query->get('id');
        $easyadmin = $this->request->attributes->get('easyadmin');
        $entity = $easyadmin['item'];
        $fields = $this->entity['edit']['fields'];

        $editForm = $this->executeDynamicMethod('create<EntityName>EditForm', [$entity, $fields]);
        $editForm->handleRequest($this->request);
        if ($editForm->isSubmitted() && $editForm->isValid()) {

            $values = $this->request->request->get(strtolower($easyadmin['entity']['name']));

            
            $images = $editForm->getData()->getImages();
            $galleryId = $editForm->getData()->getId();

            $this->syncGallery($galleryId, $images);
        }

        return parent::editAction();
    }
    
    /**
     * * @Route("/sort/{id}/{position}", name="easyadmin_sort")
     *
     * @param Request $request
     *
     * @return RedirectJsonResponse|JsonResponse
     *
     * @throws ForbiddenActionException
     */
    public function sortAction(Request $request, $id, $position)
    {

        // This is optional.
        // Only include it if the function is reserved for ajax calls only.
        if (!$request->isXmlHttpRequest()) {
            return new JsonResponse(array(
                'status' => 'Error',
                'message' => 'Error'),
            JsonResponse::HTTP_BAD_REQUEST);
        }

        $em = $this->getDoctrine()->getManager();
        // dump($request->request->get('formData'));
        // dump($request->request->all());
        // dump($request);
        // die;
        
        $data = $request->request->all();
        $results = $data['results'];

        // dump($results);
        // dump($id);
        // dump($position);
        // dump('contact developper');
        // die;

        foreach ($results as $key => $value) {
            $imageGallery = $em->getRepository(ImageGallery::class)->find($value['id']);
            $position = $key+1;
            $imageGallery->setPosition($position);
            $em->persist($imageGallery);
            $em->flush();
        }
        
        return new JsonResponse(array(
            'status' => 'Ajaxxxx',
            'message' => 'Ajaxxxx'),
        JsonResponse::HTTP_OK);
    }

    private function syncGallery($galleryId, $images)
    {   
        $results = $this->em->getRepository(ImageGallery::class)->findBy(
            ['gallery' => $galleryId]
        );
        if(null !== $results) {
            $array = [];
            foreach($images as $image) {
                $array[$image->getId()] = $image;
            }
            
    
            $i = 0;
            foreach($results as $imageGallery) {
                
                $id = $imageGallery->getImage()->getId();
                if(isset($array[$id])) {
                    $i++;
                    $imageGallery->setPosition($i);
                    $this->em->persist($imageGallery);
                } else {
                   
                    $this->em->remove($imageGallery);
                }
            }
        } else {
            $i = 0;
            foreach($images as $image) {
                $i++;
                $imageGallery = new ImageGallery();
                $imageGallery->setImage($image);
                $imageGallery->setGallery($gallery);
                $imageGallery->setPosition($i);
                $this->em->persist($imageGallery);
            }
        }
        $this->em->flush();
    }
}
