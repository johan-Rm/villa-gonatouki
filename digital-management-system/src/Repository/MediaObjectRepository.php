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

use App\Entity\MediaObject;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Translation\TranslatorInterface;


/**
 * ...
 *
 * @author Johan REMY <johan.remy@graines-digitales.online>
 */
class MediaObjectRepository extends EntityRepository
{
    public static function getVideos(EntityRepository $er, EntityManager $em, TokenStorageInterface $tokenStorage, TranslatorInterface $translator)
    {
        $encoding_format = 'video/youtube';
        $query = $er->createQueryBuilder('media')
          ->andWhere('media.encodingFormat = :encoding_format')
          ->setParameter('encoding_format', $encoding_format)
        ;

        return $query;
    }

    public function findByDate($dateBegin = null, $dateEnd = null)
    {
        $query = $this->createQueryBuilder('media')
            ->andWhere('DATE(media.dateCreated) BETWEEN :dateBegin AND :dateEnd')
            ->setParameter('dateEnd', $dateEnd)
            ->setParameter('dateBegin', $dateBegin)
            ->andWhere('media.isActive = 1')
        ;

        return $query->getQuery()->getResult();
    }
}
