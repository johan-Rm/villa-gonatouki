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

use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\View\View;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;


/**
* @Route("/config")
* ...
*
* @author Johan REMY <johan.remy@graines-digitales.online>
*/
class ConfigController extends FOSRestController
{
    /**
     * @Get("/image_filters", name="config_filters")
     * @Rest\View()
     * @Method({"GET","OPTIONS"})
     *
     **/
    public function getSchemaImageFiltersAction(Request $request)
    {
        $filter_sets = $this->container->getParameter('liip_imagine.filter_sets');
        /**
         * Create JSON View.
         */
        $view = View::create($filter_sets);
        $view->setFormat('json');

        return $view;
    }
}
