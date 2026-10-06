<?php

/*
 * This file is part of the Graines Digitales DMS project.
 *
 * (c) Johan REMY <johan.remy@graines-digitales.online>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Service\Menu;

use App\Entity\WebPage;
use Doctrine\ORM\EntityManager;
use Knp\Menu\FactoryInterface;
use Symfony\Component\HttpFoundation\RequestStack;


/**
 * ...
 *
 * @author Johan REMY <johan.remy@graines-digitales.online>
 */
class Builder
{
    private $factory;
    private $container;

    public function __construct(EntityManager $em, FactoryInterface $factory)
    {
        $this->factory = $factory;
        $this->em = $em;
    }

    public function createMainMenu(RequestStack $requestStack)
    {
        $menu = $this->factory->createItem('root');

        $menu->addChild('Home', ['route' => 'webPage_index']);

        $webPage = $this->em->getRepository(WebPage::class)
            ->findOneById(3);

        $menu->addChild('Latest Page', [
            'route' => 'webPage_show',
            'routeParameters' => ['tag' => 'test', 'slug' => $webPage->getSlug()],
        ]);

        // create another menu item
        $menu->addChild('Pricing', ['route' => 'webPage_index']);
        // you can also add sub levels to your menus as follows
        $menu['Pricing']->addChild('Open Source', ['route' => 'webPage_index']);
        $menu['Pricing']->addChild('Sur Mesure', ['route' => 'webPage_index']);

        $menu->addChild('Contact', ['route' => 'webPage_contact']);
        $menu->addChild('Admin', ['route' => 'fos_user_security_login']);

        return $menu;
    }
}
