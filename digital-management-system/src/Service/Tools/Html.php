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

use Symfony\Component\DependencyInjection\ContainerInterface;


/**
 * ...
 *
 * @author Johan REMY <johan.remy@graines-digitales.online>
 */
class Html
{
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function isHTML($string)
    {
        return $string != strip_tags($string) ? true : false;
    }
}
