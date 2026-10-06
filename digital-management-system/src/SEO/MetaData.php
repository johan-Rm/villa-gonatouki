<?php

/*
 * This file is part of the Graines Digitales DMS project.
 *
 * (c) Johan REMY <johan.remy@graines-digitales.online>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\SEO;

use App\Entity\Accommodation;
use App\Entity\Article;
use App\Entity\Organization;
use App\Entity\WebPage;
use Doctrine\Common\Util\Inflector;
use Doctrine\ORM\EntityManager;
use EasyCorp\Bundle\EasyAdminBundle\Configuration\ConfigManager;
use Symfony\Component\DependencyInjection\ContainerInterface;


/**
 * ...
 *
 * @author Johan REMY <johan.remy@graines-digitales.online>
 */
class MetaData
{
    /** @var ConfigManager */
    private $container;
    private $config;
    private $em;
    private $organization;

    public function __construct(ContainerInterface $container, EntityManager $em)
    {
        $this->container = $container;
        $this->em = $em;
        $this->organization = $this->em->getRepository(Organization::class)->findOneById(1);
    }

    public function process($entity)
    {
        if ($entity instanceof Accommodation) {
            if (null !== $entity->getNature()
                && 'location' == $entity->getNature()->getSlug()
            ) {
                $metaTitle = substr($this->buildLocationMetaTitle($entity), 0, 70);
                $entity->setMetaTitle($metaTitle);
                $metaDescription = substr($this->buildLocationMetaDescription($entity), 0, 160);
                $entity->setMetaDescription($metaDescription);
            } elseif (null !== $entity->getNature()
                && 'vente' == $entity->getNature()->getSlug()
            ) {
                $metaTitle = substr($this->buildVenteMetaTitle($entity), 0, 70);
                $entity->setMetaTitle($metaTitle);
                $metaDescription = substr($this->buildVenteMetaDescription($entity), 0, 160);
                $entity->setMetaDescription($metaDescription);
            }

            if (empty($entity->getHeadline())) {
                $entity->setHeadline($entity->getMetaTitle());
            }
        }

        if ($entity instanceof Article) {
            $metaTitle = substr($entity->getHeadline(), 0, 70);
            $entity->setMetaTitle($metaTitle);
            $metaDescription = $this->shapeSpace_truncate_string_at_word(
                $entity->getPushForward(), 160, ' ', ''
            );
            $entity->setMetaDescription($metaDescription);
        }

        if ($entity instanceof WebPage) {
            $metaTitle = substr($entity->getHeadline(), 0, 70);
            $entity->setMetaTitle($metaTitle);
            $metaDescription = $this->shapeSpace_truncate_string_at_word(
                $entity->getPushForward(), 160, ' ', ''
            );
            $entity->setMetaDescription($metaDescription);
        }

        return $entity;
    }

    public function defineAlt($media)
    {
        return $media->getName();
    }

    public function defineSEOAltBalise($media)
    {
        $siteTitle = $this->container->getParameter('siteweb.title');

        return $media->getName().' - '.$siteTitle;
    }

    private function buildLocationMetaTitle($entity)
    {
        // Location appartement, 25.47 m² T-1 à Aurillac, 369 € | Orpi
        $metaTitle = ucfirst(
            $entity->getNature()->getName()
        );
        if (null !== $entity->getType()->getName()) {
            $metaTitle .= ' - '.ucfirst(
                $entity->getType()->getName()
            );
        }
        if (null !== $entity->getFloorSize()) {
            $metaTitle .= ' - '.$entity->getFloorSize().' m²';
        }
        if (null !== $entity->getPlace()->getName()) {
            $metaTitle .= ' - '.Inflector::ucwords($entity->getPlace()->getName());
        }
        if (null !== $entity->getPrice()) {
            $metaTitle .= ' - '.$entity->getPrice().' €';
        }

        return $metaTitle;
    }

    private function buildLocationMetaDescription($entity)
    {
        // Appartement, 25.47 m² T-1 à louer à Aurillac pour 369 € avec Orpi
        $metaDescription = ucfirst(
            $entity->getType()->getName()
        );

        if (null !== $entity->getFloorSize()) {
            $metaDescription .= ', '.$entity->getFloorSize().' m²';
        }
        $metaDescription.' à louer';
        if (null !== $entity->getPlace()->getName()) {
            $metaDescription .= ' à '.Inflector::ucwords(
                $entity->getPlace()->getName()
            );
        }
        if (null !== $entity->getPrice()) {
            $metaDescription .= ' pour '.$entity->getPrice().' €';
        }
        if (null !== $this->organization) {
            $metaDescription .= ' avec '.$this->organization->getName();
        }

        return $metaDescription;
    }

    private function buildVenteMetaTitle($entity)
    {
        $metaTitle = ucfirst(
            $entity->getNature()->getName()
        );
        if (null !== $entity->getType()->getName()) {
            $metaTitle .= ' - '.ucfirst(
                $entity->getType()->getName()
            );
        }
        if (null !== $entity->getPlace()->getName()) {
            $metaTitle .= ' - '.Inflector::ucwords($entity->getPlace()->getName());
        }
        if (null !== $entity->getFloorSize()) {
            $metaTitle .= ' - '.$entity->getFloorSize().' m²';
        }
        if (null !== $entity->getPrice()) {
            $metaTitle .= ' - '.$entity->getPrice().' €';
        }

        return $metaTitle;
    }

    private function buildVenteMetaDescription($entity)
    {
        // Appartement, 40 m² T-2 à acheter à Marseille 13 pour 100000 € avec Orpi
        $metaDescription = ucfirst(
            $entity->getType()->getName()
        );
        if (null !== $entity->getFloorSize()) {
            $metaDescription .= ', '.$entity->getFloorSize().' m²';
        }
        $metaDescription.' à acheter';
        if (null !== $entity->getPlace()->getName()) {
            $metaDescription .= ' à '.Inflector::ucwords(
                $entity->getPlace()->getName()
            );
        }
        if (null !== $entity->getPrice()) {
            $metaDescription .= ' pour '.$entity->getPrice().' €';
        }
        if (null !== $this->organization) {
            $metaDescription .= ' avec '.$this->organization->getName();
        }

        return $metaDescription;
    }

    private function shapeSpace_truncate_string_at_word($string, $limit, $break = '.', $pad = '...')
    {
        if (strlen($string) <= $limit) {
            return $string;
        }

        if (false !== ($max = strpos($string, $break, $limit))) {
            if ($max < strlen($string) - 1) {
                $string = substr($string, 0, $max).$pad;
            }
        }

        return $string;
    }
}
