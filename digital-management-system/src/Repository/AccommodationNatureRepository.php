<?php

/*
 * This file is part of the Graines Digitales DMS project.
 *
 * (c) Johan REMY <johan.remy@graines-digitales.online>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Repository;

use App\Entity\AccommodationNature;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Translation\TranslatorInterface;


/**
 * ...
 *
 * @author Johan REMY <johan.remy@graines-digitales.online>
 */
class AccommodationNatureRepository extends EntityRepository
{
    public static function getLocation(EntityRepository $er, EntityManager $em, TokenStorageInterface $tokenStorage, TranslatorInterface $translator)
    {
        $criteria = [
            'slug' => 'location',
        ];
        $accommodationNatureRepository = $em->getRepository(AccommodationNature::class);
        $parent = $accommodationNatureRepository->findBy(['slug' => 'location']);
        $query = $er->createQueryBuilder('accommodationNature')
               ->andWhere('accommodationNature.parent = :parent')
               ->setParameter('parent', $parent)
            ;

        return $query;
    }

    public static function getMain(EntityRepository $er, EntityManager $em, TokenStorageInterface $tokenStorage, TranslatorInterface $translator)
    {
        $query = $er->createQueryBuilder('accommodationNature')
           ->andWhere('accommodationNature.parent IS NULL')
        ;

        return $query;
    }
}
