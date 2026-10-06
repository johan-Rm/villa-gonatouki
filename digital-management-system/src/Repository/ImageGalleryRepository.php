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

use App\Entity\ImageGallery;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;


/**
 * @method ImageGallery|null find($id, $lockMode = null, $lockVersion = null)
 * @method ImageGallery|null findOneBy(array $criteria, array $orderBy = null)
 * @method ImageGallery[]    findAll()
 * @method ImageGallery[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 *
 * @author Johan REMY <johan.remy@graines-digitales.online>
 */
class ImageGalleryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ImageGallery::class);
    }
}
