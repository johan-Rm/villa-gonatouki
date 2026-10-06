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

use App\Entity\Accommodation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;


/**
 * @method Accommodation|null find($id, $lockMode = null, $lockVersion = null)
 * @method Accommodation|null findOneBy(array $criteria, array $orderBy = null)
 * @method Accommodation[]    findAll()
 * @method Accommodation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 *
 * @author Johan REMY <johan.remy@graines-digitales.online>
 */
class AccommodationRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Accommodation::class);
    }

    public function getColumnsForCsv($class)
    {
        $columns[$class] = [
            'reference' => function (Accommodation $accommodation) {
                return $accommodation->getReference();
            },
            'price' => function (Accommodation $accommodation) {
                return $accommodation->getPrice();
            },
        ];

        return $columns[$class];
    }

    public function findByRentingCriteria($criteria)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.nature = :nature')
            ->setParameter('nature', $criteria['nature'])
            ->andWhere('a.duration = :duration')
            ->setParameter('duration', $criteria['duration'])
            ->andWhere('a.reference IS NOT NULL')
            ->getQuery()
            ->getResult()
        ;
    }

    public function findBySellingCriteria($criteria)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.nature = :nature')
            ->setParameter('nature', $criteria['nature'])
            ->andWhere('a.type = :type')
            ->setParameter('type', $criteria['type'])
            ->andWhere('a.reference IS NOT NULL')
            ->getQuery()
            ->getResult()
        ;
    }
}
