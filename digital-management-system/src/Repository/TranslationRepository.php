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

use Doctrine\ORM\EntityRepository;


/**
 * ...
 *
 * @author Johan REMY <johan.remy@graines-digitales.online>
 */
class TranslationRepository extends EntityRepository
{
    public function findWithoutSlug($lang)
    {
        return $this->createQueryBuilder('t')
            ->where('t.fieldName != :field_name')
            ->setParameter('field_name', 'slug')
            ->andWhere('t.lang = :lang')
            ->setParameter('lang', $lang)
            ->getQuery()
            ->getResult()
        ;
    }

    public function findWithSlug($lang)
    {
        return $this->createQueryBuilder('t')
            ->where('t.fieldName = :field_name')
            ->setParameter('field_name', 'slug')
            ->andWhere('t.lang = :lang')
            ->setParameter('lang', $lang)
            ->getQuery()
            ->getResult()
        ;
    }
}
