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

use App\Entity\AccommodationTrait;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;


/**
 * @method AccommodationTrait|null find($id, $lockMode = null, $lockVersion = null)
 * @method AccommodationTrait|null findOneBy(array $criteria, array $orderBy = null)
 * @method AccommodationTrait[]    findAll()
 * @method AccommodationTrait[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 *
 * @author Johan REMY <johan.remy@graines-digitales.online>
 */
class AccommodationTraitRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, AccommodationTrait::class);
    }
}
